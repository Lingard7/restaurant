//�@PDO�ɂ��MSQL�ڑ��ݒ�t�@�C��
<?php
function getDb(){
    $dsn = 'mysql:dbname=table; host=127.0.0.1; charset=utf8';		//�@�e�[�u�����C�z�X�gIP�C�����R�[�h
    $usr = 'user';								//�@���O�C�����[�U��
    $pass = 'password';							//�@���O�C���p�X���[�h

    $db = new PDO($dsn, $usr, $pass);					//�@�ڑ�
    return $db;
}
?>