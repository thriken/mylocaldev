<?php
/*���ֲ���*/
/*����hashֵ*/
$music_hash="91b5a3ba1420b1156afd7918c1af27e1";
 
/*�ɻ�ȡ������ѵĿṷ���֣�������*/
echo "http://trackercdn.kugou.com/i/v2/?cmd=25&key=".md5(strtolower($music_hash)."kgcloudv2")."&hash={$music_hash}&pid=1&behavior=download<br/>";
 
/*�ɻ�ȡ������ѵĿṷ���֣������𣩼��շ����ֵ�128Kbps���ʣ��� �蹺��ר�����շ����֣�*/
$mid="0";//���� ����
$userid="0";//���� ����
$appid="1005";//����1005
echo "http://trackercdn.kugou.com/i/v2/?cmd=26&key=".md5(strtolower($music_hash)."kgcloudv2".$appid.$mid.$userid)."&hash={$music_hash}&pid=1&behavior=play&mid={$mid}&appid={$appid}&userid={$userid}&version=0&vipType=0&token=0<br>";
 
/*MV����*/
/*MV HASHֵ*/
$mv_hash="1855DD770F35100F4BEF9C4634B42D55";
echo     "http://trackermv.kugou.com/interface/index/cmd=4&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kgcloud")."&ext=mp4<br/>";echo "http://trackermv.kugou.com/interface/index/cmd=100&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kugoumvcloud")."&pid=6&ext=mp4&ismp3=0<br/>";
echo "http://trackermv.kugou.com/interface/index/cmd=103&hash=".strtoupper($mv_hash)."&key=".md5(strtoupper($mv_hash)."kugoumvcloud")."&pid=6&ext=mp4&ismp3=0<br/>";
echo "http://trackermv.kugou.com/interface/index/cmd=104&hash=".strtoupper($mv_hash)."&ext=mp4<br/>";
 
 
?>