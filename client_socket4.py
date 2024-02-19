#画像処理
import cv2
import numpy as np

#通信
#import socket
import time
import asyncio
import websockets

import RPi.GPIO as GPIO
import time

GPIO_PIN=26					#人感センサのピン番号       人感センサのOUTに接続!

GPIO.setmode(GPIO.BCM)
GPIO.setup(GPIO_PIN,GPIO.IN)

#カメラ
from picamera2 import Picamera2
from libcamera import controls

#IPアドレス・ポート番号
#host = "192.168.10.51"
#port = 44444


#カメラ接続
picam2 = Picamera2()
picam2.configure(picam2.create_preview_configuration(main={"format": 'XRGB8888', "size": (640, 480)}))
picam2.start()

#カメラを連続オートフォーカスモードにする
#picam2.set_controls({"AfMode": controls.AfModeEnum.Continuous})

zukei = np.full((210, 425, 3), 128, dtype=np.uint8)	#判定表示するウィンドウ


async def hello(websocket):
    i = 0  #フラグ
    j = 0  #人感センサの判定


    num = 0
    while True:
      num = num + 1

      im = picam2.capture_array()	#画像を取得
      grey = cv2.cvtColor(im, cv2.COLOR_BGR2GRAY)	#白黒に変換

      #cv2.imshow("Camera", im)		#カラー映像表示
      #cv2.imshow("Camera_grey", grey)	#白黒映像表示

      #最初に取得した画像を保持する（この画像を元画像として比較を行う）
      if i < 1:
        avg = grey.copy().astype("float")
        i = 1
        continue

      #二値化する
      frameDelta = cv2.absdiff(grey,cv2.convertScaleAbs(avg))
      thresh = cv2.threshold(frameDelta,25 , 255,cv2.THRESH_BINARY)[1]
      #cv2.imshow("thresh", thresh)	#二値化映像を表示

      #二値化画像の白の割合を計算
      white = cv2.countNonZero(thresh)
      whiteArea = (cv2.countNonZero(thresh)/307200)*100
      
      #人感センサ1
      if(GPIO.input(GPIO_PIN) == GPIO.HIGH):    #人検知あり時
                print("2") 
                j = 1
      else:					#人検知なし時
                print("3")
                j = 0
                
      if j == 1:
          await websocket.send("2")
      else:
          await websocket.send("3")
      
    
      if num%50 == 0:				#50回ループ
      
         if whiteArea > 10:			#カメラで白の割合が10以上の時
            await websocket.send("1")
            print("1")
            cv2.rectangle(zukei,(50,10),(125,60),(0,0,255),thickness=-1)

         else:
            await websocket.send("0")
            print("0")
            cv2.rectangle(zukei,(50,10),(125,60),(0,255,0),thickness=-1)
      
      #key = cv2.waitKey(1)
      # Escキーを入力されたら画面を閉じる
      #if key == 27:
        #break

#ウェブブラウザと通信    
async def main():
    async with websockets.serve(hello,"localhost",8765):
        await asyncio.Future()

asyncio.run(main())

