===============================================================================
 [Program Name] DICOM AiViewer (Java Applet) Ver.1.0.0��
 [Archive Name] dicomaiviewer100.zip
 [Author] �����q �M�m(���Ƃ��� �����Ђ�)
          E-mail: katoco@mars.elcom.nitech.ac.jp or katoco@tomo.ne.jp
 [Edit By] E-mail: ta-naka@mars.elcom.nitech.ac.jp
 [Web site] http://mars.elcom.nitech.ac.jp/dicom/
 [Development Language] Sun SDK 1.4.2_06
===============================================================================

 *****************************************************************************

     ���� DICOM AiViewer �́AInternetExplorer��NetscapeNavigator�ȂǂƂ�����
  �ėp��WWW�u���E�U���g�p���āA DICOM�t�@�C���̉{���A�������\�Ƃ�����̂ł��B


  !!! �x�� !!!
  �ȉ��̃\�t�g�E�F�A�́A�t���[�E�F�A�ł���A���p�E�񏤗p�Ɋւ�炸�g�p���邱�Ƃ��ł��܂��B
  �������A�{�\�t�g�E�F�A���g�p���ĉ��炩�̓���ُ��@����A���C�Z���X��̖�肪�����Ă��A
  �����͈�؊֒m���܂���B
  �Ȃ��A���쌠�ɂ��Ă� COPYRIGHT.txt ���䗗�������B 

 *****************************************************************************

[��ȋ@�\]
  * DICOM�f�[�^�̉{��
  * Java����ɂ��JavaApplet�ŊJ�����Ă��邽�߁A
    Windows�AMacOS�AUnix���œ���\
  * 8�`16�r�b�g�O���[�X�P�[���̃T�|�[�g
  * 24�r�b�gRGB�J���[�̃T�|�[�g
  * 1000���ȏ�̃}���`�X���C�X�̓ǂݍ���
  * ���A���^�C�� WW/WL
  * �g��E�k��
  * ���ዾ�@�\
  * �X���C�X�A���\���i�V�l���[�h�j
  * �^�O���̕\��
  * ��ʕ���
  * �摜�̔������]�A���E���]�A�㉺���]�A90����]
  * New!:DICOM-Part10�K�i�ɑΉ�
  * New!:JPEG�̓]���\���ɑΉ�

[�g�p���@]
  �N���X�t�@�C�����_�E�����[�h���AWeb����Q�Ƃł���ȉ��̗�̂悤��
  �f�B���N�g���ɉ𓀂��Ă��������B�����āAHTML�̋L�q�̎d���ɂ�����HTML�t�@�C��
  ��p�ӂ��܂��B���Ƃ́A WWW�u���E�U����쐬����HTML�t�@�C���ɃA�N�Z�X����ƁA
  DICOM AiViewer ���N�����܂��B
  �����ӁFWWW�u���E�U���N�����Ă���}�V�����HTML�t�@�C�������݂���ꍇ���A
    �A�N�Z�X�ɂ͕K�� http://localhost/sample.html �̂悤�ɋL�q���Ahttp ��
    �A�N�Z�X����悤�ɂ��Ă��������B

  ��:

  /public_html/�Ȃ� �iWeb�\������t�H���_�j
       |
       | -- sample.html�i�����ɁuHTML�̋L�q�̎d���v���Q�Ƃɍ쐬����
       | -- �Ȃ�         HTML�t�@�C����u���j
       |
       | -- /dicomviewer/ �i�����Ƀ_�E�����[�h����ClassFile��W�J����j
       |        |
       |        | -- Viewer.class
       |        | -- �Ȃ�
       |
       | -- /data/ �i������DICOM�t�@�C���������Ƃ悢�����j
                |
                | -- sample.dcm
                | -- �Ȃ�

[Java �̐ݒ�]
Sun SDK 1.4.2_06�ɂ��J�����s�������߁ASun��JRE 1.4.2_06�ȏ���C���X�g�[�����Ă��������B
   -->http://java.sun.com/j2se/1.4.2/ja/download.html

JPEG�ɂ��]���\����JPEG�t���k���ł͂���ɐݒ肪�K�v�ł��B
Java Advanced Imaging Image I/O Tools �𓱓����Ă��������B
   -->http://java.sun.com/products/java-media/jai/index.jsp

����ɁA.java.policy�ݒ�t�@�C����ݒ肵Applet��JAI ImageI/O Tools�����p�ł���悤�ɂ��܂��B
JRE���C���X�g�[����̃t�H���_���uC:\Program Files\java\jre�v�Ƃ�����Ő������܂��B
�uC:\Program Files\java\jre\lib\security�v���ɂ���.java.policy�t�@�C����
Windows:�uC:\Documents and Settings\User�v
UNIX   :�u/home/User/�v
�ɃR�s�[���Ă��������B
���̌�.java.policy�ɉ��L��ǋL���Ă��������B
�ǋL��:
   grant codeBase "http://hogehoge.ho.ge/" {                                   //Applet��u���Ă���URL
      permission java.lang.RuntimePermission "loadLibrary.*";                  //�Œ�
      permission java.util.PropertyPermission "*", "read";                     //�Œ�
      permission java.io.FilePermission "C:\\Program Files\\java\\-", "read";  //JRE��u���Ă��郍�[�J���t�H���_
   };

�܂��AJPEG�摜���A�Ή����Ă���摜�̎�ނɂ��Ă͉��L��URL�Ŋm�F�ł��܂��B
   -->http://mars.elcom.nitech.ac.jp/dicom/check-jpeg.html

���̎��A.java.policy�ɉ��L��ǋL���Ă��������B
�ǋL��F
  grant codeBase "http://mars.elcom.nitech.ac.jp/dicom/"{                    //�Œ�
    permission java.lang.RuntimePermission "loadLibrary.*";                  //�Œ�
    permission java.util.PropertyPermission "*", "read";                     //�Œ�
    permission java.io.FilePermission "C:\\Program Files\\java\\-", "read";  //JRE��u���Ă��郍�[�J���t�H���_
  };

�ݒ肪���������[JPEG 2000],[JPEG-LOSSLESS]���摜�̎�ނ��������o�܂��B
�o�Ȃ��ꍇ�͐ݒ���������Ă��������B

[HTML�̋L�q�̎d��]
  * <APPLET>
    - CODE: "dicomviewer.Viewer.class"�̂܂܋L�q���Ă��������B
    - NAME: "Viewer.java"�̂܂܋L�q���Ă��������B
    - WIDTH: Viewer��\�����镝���w�肵�܂��B
    - HEIGHT: Viewer��\�����鍂���ł��B
  * <PARAM>
    - tmpSize: ��x�Ƀ�������Ƀ��[�h���閇���i�f�t�H���g��10�j
    - NUM: �ǂݍ��ޑS�̖̂����i�f�t�H���g��1�j
    - currentNo: �ŏ��Ɍ���摜�ԍ��i�f�t�H���g��0�j
    - dicURL: DICOM������URL
    - imgURL??: �摜��URL

  ���ڂ���HTML�̏������ɂ��ẮAHTML�̎Q�l����ǂ�ł��������B

sample.html�i�T���v���j
-------------------------------------------------------------------------------
<HTML>
<HEAD>
<TITLE>
DICOM Viewer
</TITLE>
</HEAD>
<BODY>
<APPLET
  CODEBASE = "."
  CODE = "dicomviewer.Viewer.class"
  NAME = "Viewer.java"
  WIDTH = 100%
  HEIGHT = 100%
  HSPACE = 0
  VSPACE = 0
  ALIGN = middle >
<PARAM NAME = "tmpSize" VALUE = "10">
<PARAM NAME = "NUM" VALUE = "5">
<PARAM NAME = "currentNo" VALUE = "0">
<PARAM NAME = "dicURL" VALUE = "http://hoge.com/dicom/dicomviewer/Dicom.dic">
<PARAM NAME = "imgURL0" VALUE = "http://hoge.com/dicom/data/CT.531.1.dcm">
<PARAM NAME = "imgURL1" VALUE = "http://hoge.com/dicom/data/CT.531.2.dcm">
<PARAM NAME = "imgURL2" VALUE = "http://hoge.com/dicom/data/CT.531.3.dcm">
<PARAM NAME = "imgURL3" VALUE = "http://hoge.com/dicom/data/CT.531.4.dcm">
<PARAM NAME = "imgURL4" VALUE = "http://hoge.com/dicom/data/CT.531.5.dcm">
</APPLET> 
</BODY>
</HTML> 
-------------------------------------------------------------------------------

[���쌠]
  Copyright(C) 2000,
  Nagoya Institute of Technology, Iwata laboratory and Takahiro Katoji

  �{�v���O�����̑S�Ă̒��쌠�́A���É��H�Ƒ�w�d�C���H�w�� ��c������
  ����сA�����q �M�m ���L���܂��B

  �{�v���O�����̓t���[�E�\�t�g�E�F�A�ł��B���Ȃ��́AFree Software Foundation 
  �����\����GNU ��ʌ��L�g�p�����́u�o�[�W�����Q�v�����͂���ȍ~�̊e�o�[�W��
  ���̒����炢���ꂩ��I�����A���̃o�[�W��������߂�����ɏ]���Ė{�v���O����
  ���ĔЕz�܂��͕ύX���邱�Ƃ��ł��܂��B

  �{�v���O�����͗L�p�Ƃ͎v���܂����A�Еz�ɂ������ẮA�s�ꐫ�y�ѓ���ړI�K��
  ���ɂ��Ă̈Öق̕ۏ؂��܂߂āA�����Ȃ�ۏ؂��s�Ȃ��܂���B�ڍׂɂ��Ă�
  GNU ��ʌ��L�g�p�����������ǂ݂��������B

[�ӎ�]
  �{�v���O�����̍쐬�ɓ�����A�����̌�w���A�䏕���𒸂��܂������c�ی��q����
  �w��w�� �]�{ �L �u�t�A���c�ی��q����w�q���w�� ���� �W�� �u�t�A���c�ی��q
  ����w������Ȋw������ ���� �G�a ����ɐ[�����Ӓv���܂��B

  �܂��A�{�v���O�����͖��É��H�Ƒ�w�d�C���H�w�� ��c �� �������n�߂Ƃ��āA
  ��c�������̏����Ȃ�тɑ��Ɛ��̊F�l�������ɂ͐��������Ȃ��������Ƃ����߂�
  �F�m����ƂƂ��ɁA�F�l���Ɋ��Ӓv���܂��B