VERSION 5.00
Object = "{248DD890-BB45-11CF-9ABC-0080C7E7B78D}#1.0#0"; "MSWINSCK.OCX"
Begin VB.Form Form1 
   Caption         =   "Form1 v2.5"
   ClientHeight    =   10935
   ClientLeft      =   120
   ClientTop       =   450
   ClientWidth     =   8745
   LinkTopic       =   "Form1"
   ScaleHeight     =   10935
   ScaleWidth      =   8745
   StartUpPosition =   3  '����ȱʡ
   Begin VB.TextBox txtWatchServerIP 
      Height          =   270
      Left            =   5640
      TabIndex        =   3
      Text            =   "192.168.168.101"
      Top             =   960
      Width           =   1695
   End
   Begin VB.TextBox txtWatchServerPort 
      Height          =   270
      Left            =   5640
      TabIndex        =   4
      Text            =   "61005"
      Top             =   1440
      Width           =   855
   End
   Begin VB.TextBox txtIP 
      Height          =   270
      Left            =   5640
      TabIndex        =   2
      Text            =   "192.168.168.123"
      Top             =   480
      Width           =   1695
   End
   Begin VB.TextBox txtSN 
      Height          =   270
      Left            =   5640
      TabIndex        =   1
      Text            =   "229999901"
      Top             =   120
      Width           =   1215
   End
   Begin MSWinsockLib.Winsock WinsockServer 
      Left            =   3960
      Top             =   0
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   393216
      Protocol        =   1
   End
   Begin VB.TextBox Text1 
      Height          =   9015
      Left            =   360
      MultiLine       =   -1  'True
      ScrollBars      =   3  'Both
      TabIndex        =   5
      Top             =   1920
      Width           =   8175
   End
   Begin VB.CommandButton Command1 
      Caption         =   "1. Test Basic Function"
      Height          =   495
      Left            =   960
      TabIndex        =   0
      Top             =   240
      Width           =   2895
   End
   Begin MSWinsockLib.Winsock Winsock1 
      Left            =   240
      Top             =   240
      _ExtentX        =   741
      _ExtentY        =   741
      _Version        =   393216
      Protocol        =   1
   End
   Begin VB.Label Label4 
      Caption         =   "watchServer IP"
      Height          =   255
      Left            =   4080
      TabIndex        =   8
      Top             =   960
      Width           =   1575
   End
   Begin VB.Label Label3 
      Caption         =   "watchServerPort"
      Height          =   255
      Left            =   3960
      TabIndex        =   9
      Top             =   1440
      Width           =   1335
   End
   Begin VB.Label Label2 
      Caption         =   "IP"
      Height          =   255
      Left            =   5160
      TabIndex        =   7
      Top             =   480
      Width           =   375
   End
   Begin VB.Label Label1 
      Caption         =   "SN"
      Height          =   255
      Left            =   5160
      TabIndex        =   6
      Top             =   120
      Width           =   375
   End
End
Attribute VB_Name = "Form1"
Attribute VB_GlobalNameSpace = False
Attribute VB_Creatable = False
Attribute VB_PredeclaredId = True
Attribute VB_Exposed = False
'/**
'* WGBasicTestVB 2015-04-29 20:41:30 karl CSN ������ $
'*
'* �Ž������� �̱���Э�� ���԰���
'* V1.4 �汾  2014-09-20 18:04:38
'*            ��Ҫʹ�� Winsock �ؼ������ [Mswinsck.ocx   Microsoft Winsock Control 6.0 (SP6)]
'*            ��������:  ��ѯ������״̬
'*                       ��ȡ����ʱ��
'*                       ��������ʱ��
'*                       ��ȡָ�������ŵļ�¼
'*                       �����Ѷ�ȡ���ļ�¼������
'*                       ��ȡ�Ѷ�ȡ���ļ�¼������
'*                       Զ�̿���
'*                       Ȩ����ӻ��޸�
'*                       Ȩ��ɾ��(����ɾ��)
'*                       Ȩ�����(ȫ�����)
'*                       Ȩ��������ȡ
'*                       Ȩ�޲�ѯ
'*                       �����ſ��Ʋ���(����/��ʱ)
'*                       ��ȡ�ſ��Ʋ���(����/��ʱ)
'
'*                       ���ý��շ�������IP�Ͷ˿�
'*                       ��ȡ���շ�������IP�Ͷ˿�
'*
'*
'*                       ���շ�������ʵ�� (��61005�˿ڽ�������) -- ����� һ��Ҫע�����ǽ���� ����������������ݵ�.
'* V2.5 �汾  2015-04-29 20:41:30 ���� V6.56�����汾 �ͺ���0x19��Ϊ0x17
'*/


Private sendSequenceId As Long       '����ָ�����ˮ��

Const WGPacketSize = 64              '���ĳ���
Const WGPacketType = &H17            '����
Const ControllerPort = 60000         '�������˿�
Const SpecialFlag = &H55AAAA55       '�����ʶ ��ֹ�����

Private buff(63) As Byte             '���ݽ��ջ�����(64�ֽ�)

Private watchingrecordIndex As Long  '���������ʱ����ļ�¼������
 
 '��¼ԭ�� (������ SwipePass ��ʾͨ��; SwipeNOPass��ʾ��ֹͨ��; ValidEvent ��Ч�¼�(�簴ť �Ŵ� �������뿪��); Warn �����¼�)
Private RecordDetails()

'�������ݰ�/�������ݰ�
Private Function pktrun(ByRef ASendBuff() As Byte, ByRef BReceiveBuff() As Byte, Optional ByVal timeoutMs As Integer = 400) As Integer
    Dim tries As Integer
    Dim ret As Integer

    ret = arrayReset(BReceiveBuff, WGPacketSize)
    sendSequenceId = sendSequenceId + 1
    ret = IntToByte(sendSequenceId, ASendBuff, 40, 4) '���
    tries = 3
    ret = -1
    Dim doeventCount As Integer
    doeventCount = 1000
    Do While tries > 0
        Dim T As Long
        Me.Winsock1.SendData (ASendBuff)
        T = GetTickCount()
        Do
        Sleep (1)
            If (Me.Winsock1.BytesReceived = WGPacketSize) Then
                Me.Winsock1.GetData BReceiveBuff, vbArray + vbByte, WGPacketSize
                '�������, ���ܺ�, ��ˮ��Ҫһ��
                If ((ASendBuff(0) = BReceiveBuff(0)) And (ASendBuff(1) = BReceiveBuff(1)) And (ASendBuff(40) = BReceiveBuff(40)) And (ASendBuff(41) = BReceiveBuff(41)) And (ASendBuff(42) = BReceiveBuff(42)) And (ASendBuff(43) = BReceiveBuff(43))) Then
                    ret = 1
                    Exit Do
                End If
            End If
            If (GetTickCount - T >= 5) Then
               doeventCount = doeventCount + 1
               If (doeventCount >= 1000) Then
               doeventCount = 0
                 DoEvents
               End If
            End If
        Loop Until GetTickCount - T >= timeoutMs  'ȱʡ400ms��ʱ

        If (ret > 0) Then
            Exit Do
        End If
        tries = tries - 1
    Loop

    If (ret > 0) Then
        Dim i As Integer
        For i = 0 To WGPacketSize - 1
            buff(i) = BReceiveBuff(i)
        Next i
    End If

    pktrun = ret
End Function


'��¼��Ϣ
Private Function log(ByVal info As String)
    Me.Text1.Text = Me.Text1.Text & info & vbCrLf
    Text1.SelLength = 1
    Text1.SelStart = Len(Text1.Text) '���������һ��
    log = Me.Text1.Text
End Function


'ʵ�ʻ�ȡ��������
Private Sub getReceiveBuffData(ByRef BReceiveBuff() As Byte)
    Dim i As Integer
    For i = 0 To WGPacketSize - 1
        BReceiveBuff(i) = buff(i)
    Next i
End Sub


'��ť�¼�
Private Sub Command1_Click()
    Dim controllerSN As Long
    Dim controllerIP As String
    Dim watchServerIP As String
    Dim watchServerPort As Long


    '    '������δ������������  �� ����IP�Ĺ���  (ֱ����IP���ù��������)
    '    '�������в���˵��
    '    '������SN  = 229999901
    '    '������IP  = 192.168.168.123
    '    '����  IP  = 192.168.168.101
    '    '������Ϊ���շ�������IP (������IP 192.168.168.101), ���շ������˿� (61005)

    controllerSN = Me.txtSN.Text ' 229999901
    controllerIP = Me.txtIP.Text '"192.168.168.123"
    watchServerIP = txtWatchServerIP.Text '"192.168.168.101"
    watchServerPort = Me.txtWatchServerPort.Text ' 61005
    
    log ("controllerSN = " & controllerSN)
    log ("controllerIP = " & controllerIP)
    log ("watchServerIP = " & watchServerIP)
    log ("watchServerPort = " & watchServerPort)
    log (vbCrLf)

 '��¼ԭ�� (������ SwipePass ��ʾͨ��; SwipeNOPass��ʾ��ֹͨ��; ValidEvent ��Ч�¼�(�簴ť �Ŵ� �������뿪��); Warn �����¼�)
    '����  ����   Ӣ������  ��������
     RecordDetails = Array("1", "SwipePass", "Swipe", "ˢ������", "2", "SwipePass", "Swipe Close", "ˢ����", "3", "SwipePass", "Swipe Open", "ˢ����", "4", "SwipePass", "Swipe Limited Times", "ˢ������(���޴�)", _
"5", "SwipeNOPass", "Denied Access: PC Control", "ˢ����ֹͨ��: ���Կ���", "6", "SwipeNOPass", "Denied Access: No PRIVILEGE", "ˢ����ֹͨ��: û��Ȩ��", "7", "SwipeNOPass", "Denied Access: Wrong PASSWORD", "ˢ����ֹͨ��: ���벻��", "8", "SwipeNOPass", "Denied Access: AntiBack", "ˢ����ֹͨ��: ��Ǳ��", _
"9", "SwipeNOPass", "Denied Access: More Cards", "ˢ����ֹͨ��: �࿨", "10", "SwipeNOPass", "Denied Access: First Card Open", "ˢ����ֹͨ��: �׿�", "11", "SwipeNOPass", "Denied Access: Door Set NC", "ˢ����ֹͨ��: ��Ϊ����", "12", "SwipeNOPass", "Denied Access: InterLock", "ˢ����ֹͨ��: ����", _
"13", "SwipeNOPass", "Denied Access: Limited Times", "ˢ����ֹͨ��: ��ˢ����������", "14", "SwipeNOPass", "Denied Access: Limited Person Indoor", "ˢ����ֹͨ��: ������������", "15", "SwipeNOPass", "Denied Access: Invalid Timezone", "ˢ����ֹͨ��: �����ڻ�����Чʱ��", "16", "SwipeNOPass", "Denied Access: In Order", "ˢ����ֹͨ��: ��˳���������", _
"17", "SwipeNOPass", "Denied Access: SWIPE GAP LIMIT", "ˢ����ֹͨ��: ˢ�����Լ��", "18", "SwipeNOPass", "Denied Access", "ˢ����ֹͨ��: ԭ����", "19", "SwipeNOPass", "Denied Access: Limited Times", "ˢ����ֹͨ��: ˢ����������", "20", "ValidEvent", "Push Button", "��ť����", _
"21", "ValidEvent", "Push Button Open", "��ť��", "22", "ValidEvent", "Push Button Close", "��ť��", "23", "ValidEvent", "Door Open", "�Ŵ�[�Ŵ��ź�]", "24", "ValidEvent", "Door Closed", "�Źر�[�Ŵ��ź�]", _
"25", "ValidEvent", "Super Password Open Door", "�������뿪��", "26", "ValidEvent", "Super Password Open", "�������뿪", "27", "ValidEvent", "Super Password Close", "���������", "28", "Warn", "Controller Power On", "�������ϵ�", _
"29", "Warn", "Controller Reset", "��������λ", "30", "Warn", "Push Button Invalid: Disable", "��ť������: ��ť����", "31", "Warn", "Push Button Invalid: Forced Lock", "��ť������: ǿ�ƹ���", "32", "Warn", "Push Button Invalid: Not On Line", "��ť������: �Ų�����", _
"33", "Warn", "Push Button Invalid: InterLock", "��ť������: ����", "34", "Warn", "Threat", "в�ȱ���", "35", "Warn", "Threat Open", "в�ȱ�����", "36", "Warn", "Threat Close", "в�ȱ�����", _
"37", "Warn", "Open too long", "�ų�ʱ��δ�ر���[�Ϸ����ź�]", "38", "Warn", "Forced Open", "ǿ�д��뱨��", "39", "Warn", "Fire", "��", "40", "Warn", "Forced Close", "ǿ�ƹ���", _
"41", "Warn", "Guard Against Theft", "��������", "42", "Warn", "7*24Hour Zone", "����ú���¶ȱ���", "43", "Warn", "Emergency Call", "�������ȱ���", "44", "RemoteOpen", "Remote Open Door", "����ԱԶ�̿���", _
"45", "RemoteOpen", "Remote Open Door By USB Reader", "������ȷ��������Զ�̿���")



    '  ���� UDP ͨ��
    Me.Winsock1.Protocol = sckUDPProtocol
    Me.WinsockServer.Protocol = sckUDPProtocol

    testBasicFunction controllerIP, controllerSN   '�������ܲ���
    log (vbCrLf)
    log (vbCrLf)

    '(��61005�˿ڽ�������) -- ����� һ��Ҫע�����ǽ���� ����������������ݵ�.
    testWatchingServer controllerIP, controllerSN, watchServerIP, watchServerPort '���շ���������
    WatchingServerRuning watchServerIP, watchServerPort  '������������������
End Sub

  ''' ��ʾ��¼��Ϣ
    ''' </summary>
    ''' <param name="pkt"></param>
    Private Sub displayRecordInformation(ByRef recvBuff() As Byte)
        '8-11   ��¼��������
        '(=0��ʾû�м�¼)   4   0x00000000
        Dim recordIndex As Long
         recordIndex = (ByteToLong(recvBuff, 8, 4))
        '12 ��¼����**********************************************
        '0=�޼�¼
        '1=ˢ����¼
        '2=�Ŵ�,��ť, �豸����, Զ�̿��ż�¼
        '3=������¼ 1
        '0xFF=��ʾָ������λ�ļ�¼�ѱ����ǵ���.  ��ʹ������0, ȡ������һ����¼������ֵ
        Dim recordType As Integer
        recordType = recvBuff(12)
        '13 ��Ч��(0 ��ʾ��ͨ��, 1��ʾͨ��) 1
        Dim recordValid As Integer
        recordValid = recvBuff(13)
        '14 �ź�(1,2,3,4)   1
        Dim recordDoorNO As Integer
        recordDoorNO = recvBuff(14)
        '15 ����/����(1��ʾ����, 2��ʾ����) 1   0x01
        Dim recordInOrOut As Integer
        recordInOrOut = recvBuff(15)
        '16-19  ����(������ˢ����¼ʱ)
        '����(�������ͼ�¼)   4
        Dim recordCardNO As Double
        recordCardNO = (ByteToDouble(recvBuff, 16, 4))
        '20-26  ˢ��ʱ��:
        '������ʱ���� (����BCD��)������ʱ�䲿�ֵ�˵��
        Dim recordTime As String
        recordTime = "2000-01-01 00:00:00"
        recordTime = getMsDate(recvBuff(20), recvBuff(21), recvBuff(22), recvBuff(23), recvBuff(24), recvBuff(25), recvBuff(26))

        '2012.12.11 10:49:59    7
        '27 ��¼ԭ�����(���Բ� ��ˢ����¼˵��.xls���ļ���ReasonNO)
        '��������Ϣ����   1
        Dim Reason As Integer
        Reason = recvBuff(27)
        '0=�޼�¼
        '1=ˢ����¼
        '2=�Ŵ�,��ť, �豸����, Զ�̿��ż�¼
        '3=������¼ 1
        '0xFF=��ʾָ������λ�ļ�¼�ѱ����ǵ���.  ��ʹ������0, ȡ������һ����¼������ֵ
        If recordType = 0 Then
            log ("����λ= " & recordIndex & "�޼�¼")
        ElseIf recordType = 255 Then
            log (" ָ������λ�ļ�¼�ѱ����ǵ���,��ʹ������0, ȡ������һ����¼������ֵ")
        ElseIf recordType = 1 Then
            '2015-06-10 08:49:31 ��ʾ��¼����Ϊ���ŵ�����
            '����
            log ("����λ = " & recordIndex)
            log ("  ���� = " & recordCardNO)
            log ("  �ź� = " & recordDoorNO)
            log ("  ���� = " & IIf(recordInOrOut = 1, "����", "����"))
            log ("  ��Ч = " & IIf(recordValid = 1, "ͨ��", "��ֹ"))
            log ("  ʱ�� = " & recordTime)
            log ("  ԭ�� = " & getReasonDetailChinese(Reason))
        ElseIf recordType = 2 Then
            '��������
            '�Ŵ�,��ť, �豸����, Զ�̿��ż�¼
            log ("����λ = " & recordIndex & " ��ˢ����¼")
            log ("  ��� = " & recordCardNO)
            log ("  �ź� = " & recordDoorNO)
            log ("  ʱ�� = " & recordTime)
            log ("  ԭ�� = " & getReasonDetailChinese(Reason))
        ElseIf recordType = 3 Then
            '��������
            '������¼
            log ("����λ = " & recordIndex & "  ������¼")
            log ("  ��� = " & recordCardNO)
            log ("  �ź� = " & recordDoorNO)
            log ("  ʱ�� = " & recordTime)
            log ("  ԭ�� = " & getReasonDetailChinese(Reason))
        End If
               
        Text1.SelLength = 1              '��ʾ���һ��
        Text1.SelStart = Len(Text1.Text) '��ʾ���һ��

    End Sub
    

         '������Ϣ
 Private Function getReasonDetailChinese(ByVal Reason As Integer) As String
        '����
        Dim ret As String
        If Reason > 45 Then
            ret = ""
        ElseIf Reason <= 0 Then
            ret = ""
         Else
           ret = RecordDetails((Reason - 1) * 4 + 3)
          
        End If
          getReasonDetailChinese = ret
    End Function
        'Ӣ����Ϣ
    Private Function getReasonDetailEnglish(ByVal Reason As Integer) As String
        'Ӣ������
         If Reason > 45 Then
            ret = ""
         ElseIf Reason <= 0 Then
            ret = ""
         Else
           ret = RecordDetails((Reason - 1) * 4 + 2)
        End If
         getReasonDetailEnglish = ret
    End Function
    
'ControllerIP �����õĿ�����IP��ַ
'controllerSN �����õĿ��������к�
Private Sub testBasicFunction(ByVal controllerIP As String, ByVal controllerSN As Long)
    Dim sendBuff(63) As Byte    '���ݷ��ͻ�����(64�ֽ�)
    Dim recvBuff(63) As Byte     '���ݽ��ջ�����(64�ֽ�)

    Me.Winsock1.RemoteHost = controllerIP
    Me.Winsock1.RemotePort = ControllerPort '60000

    Dim ret As Integer
    Dim success As Integer

    '��������ر���
    Dim controllerTime As Date

                                        
    '1.4   ��ѯ������״̬(���ܺ�: &H20)(ʵʱ�����) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H20
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ret = pktrun(sendBuff(), recvBuff())
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        '        '��ȡ��Ϣ�ɹ�...
        success = 1
        
        
        log ("1.4 ��ѯ������״̬ �ɹ�...")
        
        '��¼��Ϣ����
        '        '      ���һ����¼����Ϣ
        displayRecordInformation recvBuff


        '       '  ������Ϣ
        Dim doorStatus(3) As Integer
        '       '28    1�����Ŵ�(0��ʾ����, 1��ʾ��) 1   &H00
        doorStatus(1 - 1) = recvBuff(28)
        '29    2�����Ŵ�(0��ʾ����, 1��ʾ��) 1   &H00
        doorStatus(2 - 1) = recvBuff(29)
        '30    3�����Ŵ�(0��ʾ����, 1��ʾ��) 1   &H00
        doorStatus(3 - 1) = recvBuff(30)
        '31    4�����Ŵ�(0��ʾ����, 1��ʾ��) 1   &H00
        doorStatus(4 - 1) = recvBuff(31)

        Dim pbStatus(3) As Integer

        '32    1���Ű�ť(0��ʾ�ɿ�, 1��ʾ����) 1   &H00
        pbStatus(1 - 1) = recvBuff(32)
        '33    2���Ű�ť(0��ʾ�ɿ�, 1��ʾ����) 1   &H00
        pbStatus(2 - 1) = recvBuff(33)
        '34    3���Ű�ť(0��ʾ�ɿ�, 1��ʾ����) 1   &H00
        pbStatus(3 - 1) = recvBuff(34)
        '35    4���Ű�ť(0��ʾ�ɿ�, 1��ʾ����) 1   &H00
        pbStatus(4 - 1) = recvBuff(35)
        '36    ���Ϻ�
        '����0 �޹���
        '������0, �й���(������ʱ��, �����������, ��Ҫ������ά��) 1
        Dim errCode As Integer
        errCode = recvBuff(36)

        '37    ��������ǰʱ��
        'ʱ    1   &H21
        '38    ��  1   &H30
        '39    ��  1   &H58

        '40-43 ��ˮ��  4
        Dim sequenceId As Long
        sequenceId = ByteToLong(recvBuff, 40, 4)

        '48
        '������Ϣ1(����ʵ��ʹ���з���)
        '���̰�����Ϣ  1
        '49    �̵���״̬  1
        Dim relayStatus As Integer
        relayStatus = recvBuff(49)

        '50    �Ŵ�״̬��8-15bitλ(��/ǿ������)
        'Bit0  ǿ������
        'Bit1  ��
        Dim otherInputStatus As Integer
        otherInputStatus = recvBuff(50)
        If ((otherInputStatus And 1) > 0) Then
            log ("ǿ������")
        End If
        If ((otherInputStatus And 2) > 0) Then
            log ("��")
        End If


        '51    V5.46�汾֧�� ��������ǰ��  1   &H13
        '52    V5.46�汾֧�� ��    1   &H06
        '53    V5.46�汾֧�� ��    1   &H22
        '��������ǰʱ��
        'Dim controllerTime As Date
        controllerTime = getMsDate(&H20, recvBuff(51), recvBuff(52), recvBuff(53), recvBuff(37), recvBuff(38), recvBuff(39))

        log ("������ʱ��:" & controllerTime)
    Else
        log ("1.4 ��ѯ������״̬ ʧ��?????...")
        Exit Sub
    End If

    '1.5   ��ȡ����ʱ��(���ܺ�: &H32) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H32
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff

        success = 1
        log ("1.5 ��ȡ����ʱ�� �ɹ�...")
        '��������ǰʱ��
        controllerTime = getMsDate(recvBuff(8), recvBuff(9), recvBuff(10), recvBuff(11), recvBuff(12), recvBuff(13), recvBuff(14))
        log ("������ʱ��:" & controllerTime)
    End If

    '1.6   ��������ʱ��(���ܺ�: &H30) **********************************************************************************
    '�����Ե�ǰʱ��У׼������.....
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H30
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    Dim pcTime As Date
    pcTime = Now()
    sendBuff(8 + 0) = GetHex(((Year(pcTime) - (Year(pcTime) Mod 100)) / 100))
    sendBuff(8 + 1) = GetHex(Year(pcTime) Mod 100)
    sendBuff(8 + 2) = GetHex(month(pcTime))
    sendBuff(8 + 3) = GetHex(day(pcTime))
    sendBuff(8 + 4) = GetHex(hour(pcTime))
    sendBuff(8 + 5) = GetHex(minute(pcTime))
    sendBuff(8 + 6) = GetHex(second(pcTime))

    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        log ("1.6  ��������ʱ�� �ɹ�...")
    End If

    '1.7   ��ȡָ�������ŵļ�¼(���ܺ�: &HB0) **********************************************************************************
    '(ȡ������ &H00000001�ļ�¼)
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &HB0
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '  (����
    '���=0, ��ȡ������һ����¼��Ϣ
    '���=&Hffffffff��ȡ�����һ����¼����Ϣ)
    '��¼�����������������˳�������, ���ɴ�&Hffffff = 16,777,215 (����1ǧ��) . ���ڴ洢�ռ�����, ��������ֻ�ᱣ�������20�����¼. �������ų���20���, �ɵ�������λ�ļ�¼�ͻᱻ����, ������ʱ��ѯ��Щ�����ŵļ�¼, ���صļ�¼���ͽ���&Hff, ��ʾ��������.
    recordIndexToGet = 1
    ret = IntToByte(recordIndexToGet, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        log ("1.7 ��ȡ����Ϊ1�ż�¼����Ϣ �ɹ�...")
        '      ����Ϊ1�ż�¼����Ϣ
                displayRecordInformation recvBuff
    End If


    '. �������� (ȡ�����һ����¼ ͨ�������� &H00000000) (��ָ���ʺ��� ˢ����¼����20��ʱ������ʹ��)
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &HB0
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '���=0, ��ȡ������һ����¼��Ϣ
    recordIndexToGet = 0
    ret = IntToByte(recordIndexToGet, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        log ("1.7 ��ȡ����һ����¼����Ϣ �ɹ�...")
        '      ����һ����¼����Ϣ
        '8-11  ��¼��������
        '(=0��ʾû�м�¼)  4   &H00000000
        recordIndex = ByteToLong(recvBuff, 8, 4)
        If recordIndex = 0 Then
            log ("ָ��������λ��û�м�¼ ")
        Else
           displayRecordInformation recvBuff
        End If
    End If



    '�������� (ȡ���µ�һ����¼ ͨ������ &Hffffffff)
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &HB0
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '���=&Hffffffff, ��ȡ������һ����¼��Ϣ
    recordIndexToGet = &HFFFFFFFF
    ret = IntToByte(recordIndexToGet, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        log ("1.7 ��ȡ���¼�¼����Ϣ �ɹ�...")
        '      ���¼�¼����Ϣ
        '8-11  ��¼��������
        '(=0��ʾû�м�¼)  4   &H00000000
        recordIndex = ByteToLong(recvBuff, 8, 4)
        If recordIndex = 0 Then
            log ("ָ��������λ��û�м�¼ ")
        Else
            displayRecordInformation recvBuff
        End If
    End If



'    '1.8   �����Ѷ�ȡ���ļ�¼������(���ܺ�: &HB2) **********************************************************************************
'    ret = arrayReset(sendBuff, WGPacketSize)
'    sendBuff(0) = WGPacketType
'    sendBuff(1) = &HB2
'    ret = IntToByte(controllerSN, sendBuff, 4, 4)
'    ' (��Ϊ�Ѷ�ȡ���ļ�¼������Ϊ5)
'    Dim recordIndexGot As Long
'    recordIndexGot = 6
'    ret = IntToByte(recordIndexGot, sendBuff, 8, 4)
'    '12    ��ʶ(��ֹ������)    1   &H55 (�̶�)
'    i = SpecialFlag
'    ret = IntToByte(i, sendBuff, 8 + 4, 4)
'    ret = pktrun(sendBuff, recvBuff)
'    success = 0
'    If (ret = 1) Then
'        getReceiveBuffData recvBuff
'        success = 1
'        log ("1.8 �����Ѷ�ȡ���ļ�¼������ �ɹ�...")
'    End If
'
'    '1.9   ��ȡ�Ѷ�ȡ���ļ�¼������(���ܺ�: &HB4) **********************************************************************************
'    ret = arrayReset(sendBuff, WGPacketSize)
'    sendBuff(0) = WGPacketType
'    sendBuff(1) = &HB4
'    ret = IntToByte(controllerSN, sendBuff, 4, 4)
'    ret = pktrun(sendBuff, recvBuff)
'    success = 0
'    If (ret = 1) Then
'        getReceiveBuffData recvBuff
'        log ("1.9 ��ȡ�Ѷ�ȡ���ļ�¼������ �ɹ�...")
'        recordIndexGot = ByteToLong(recvBuff, 8, 4)
'        success = 1
'    End If

'        '1.8   �����Ѷ�ȡ���ļ�¼������[���ܺ�: 0xB2] **********************************************************************************
'        '�ָ�����ȡ���ļ�¼, Ϊ1.9��������ȡ������׼��-- ʵ��ʹ����, �ڳ�������ʱ�Żָ�, �������ûָ�...
'     ret = arrayReset(sendBuff, WGPacketSize)
'    sendBuff(0) = WGPacketType
'    sendBuff(1) = &HB2
'    ret = IntToByte(controllerSN, sendBuff, 4, 4)
'    ' (��Ϊ�Ѷ�ȡ���ļ�¼������Ϊ0)
'    Dim recordIndexGot As Long
'    recordIndexGot = 0
'    ret = IntToByte(recordIndexGot, sendBuff, 8, 4)
'    '12    ��ʶ(��ֹ������)    1   &H55 (�̶�)
'    i = SpecialFlag
'    ret = IntToByte(i, sendBuff, 8 + 4, 4)
'    ret = pktrun(sendBuff, recvBuff)
'    success = 0
'    If (ret = 1) Then
'        getReceiveBuffData recvBuff
'        success = 1
'        log ("1.8 �����Ѷ�ȡ���ļ�¼������ �ɹ�...")
'    End If


    '1.9   ��ȡ��¼����
    '1. ͨ�� &HB4ָ�� ��ȡ�Ѷ�ȡ���ļ�¼������ recordIndex
    '2. ͨ�� &HB0ָ�� ��ȡָ�������ŵļ�¼  ��recordIndex + 1��ʼ��ȡ��¼�� ֱ����¼Ϊ��Ϊֹ
    '3. ͨ�� &HB2ָ�� �����Ѷ�ȡ���ļ�¼������  ���õ�ֵΪ����ȡ����ˢ����¼������
    '���������������裬 ������ȡ��¼�Ĳ������
    
    log ("1.9 ��ȡ��¼����    ��ʼ...")
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &HB4
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        log ("��ʼ��ȡ��¼ ...")
        recordIndexGot = ByteToLong(recvBuff, 8, 4)
        recordIndexToGetStart = recordIndexGot + 1
        recordIndexValidGet = 0

        ret = arrayReset(sendBuff, WGPacketSize)
        sendBuff(0) = WGPacketType
        sendBuff(1) = &HB0
        ret = IntToByte(controllerSN, sendBuff, 4, 4)
        i = 0
        Do While i <= 200000
            ret = IntToByte(recordIndexToGetStart, sendBuff, 8, 4)
            ret = pktrun(sendBuff, recvBuff)
            success = 0
            If (ret = 1) Then
                getReceiveBuffData recvBuff
                success = 1
                '12    ��¼����
                '0=�޼�¼
                '1=ˢ����¼
                '2=�Ŵ�,��ť, �豸����, Զ�̿��ż�¼
                '3=������¼    1
                '&HFF=��ʾָ������λ�ļ�¼�ѱ����ǵ���.  ��ʹ������0, ȡ������һ����¼������ֵ
                recordType = recvBuff(12)
                If (recordType = 0) Then
                    Exit Do
                End If 'û�и����¼

                If (recordType = &HFF) Then
                    'success = 0  '����������Ч  ������������ֵ
                    'Exit Do
                    'ȡ����һ����¼������λ
                     ret = arrayReset(sendBuff, WGPacketSize)
                    sendBuff(0) = WGPacketType
                    sendBuff(1) = &HB0
                    ret = IntToByte(controllerSN, sendBuff, 4, 4)
                    '���=0, ��ȡ������һ����¼��Ϣ
                    recordIndexToGet = 0
                    ret = IntToByte(recordIndexToGet, sendBuff, 8, 4)
                    ret = pktrun(sendBuff, recvBuff)
                    success = 0
                    If (ret = 1) Then
                        getReceiveBuffData recvBuff
                        success = 1
                        log ("1.7 ��ȡ����һ����¼����Ϣ �ɹ�...")
                        '      ����һ����¼����Ϣ
                        recordIndex = ByteToLong(recvBuff, 8, 4)
                        recordIndexToGetStart = recordIndex
                    End If
                End If
                If (success > 0) Then
                    recordIndexValidGet = recordIndexToGetStart
                    '.......���յ��ļ�¼���洢����
                    displayRecordInformation recvBuff
                    '*****
                    '###############
                    recordIndexToGetStart = recordIndexToGetStart + 1
                    i = i + 1
                End If
            Else
                Exit Do
            End If
        Loop
        If (success > 0) Then
            ret = arrayReset(sendBuff, WGPacketSize)
            sendBuff(0) = WGPacketType
            sendBuff(1) = &HB2
            ret = IntToByte(controllerSN, sendBuff, 4, 4)
            'ͨ�� &HB2ָ�� �����Ѷ�ȡ���ļ�¼������  ���õ�ֵΪ����ȡ����ˢ����¼������
            recordIndexGot = recordIndexValidGet
            ret = IntToByte(recordIndexGot, sendBuff, 8, 4)
            '12    ��ʶ(��ֹ������)    1   &H55 (�̶�)
            i = SpecialFlag
            ret = IntToByte(i, sendBuff, 8 + 4, 4)
            ret = pktrun(sendBuff, recvBuff)
            success = 0
            If (ret = 1) Then
                getReceiveBuffData recvBuff
                If (recvBuff(8) = 1) Then
                    '��ȫ��ȡ�ɹ�....
                    success = 1
                    log ("1.9 ��ȫ��ȡ�ɹ�   �ɹ�...")
                End If

            End If
        End If
    End If

    '1.10  Զ�̿���(���ܺ�: &H40) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H40
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    doorNO = 1
    sendBuff(8) = doorNO
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then
            success = 1
            '��Ч����.....
            log ("1.10 Զ�̿���   �ɹ�...")
        End If
    End If


    '1.11  Ȩ����ӻ��޸�(���ܺ�: &H50) **********************************************************************************
    '���ӿ���0D D7 37 00, ͨ����ǰ��������������
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H50
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '0D D7 37 00 Ҫ��ӻ��޸ĵ�Ȩ���еĿ��� = &H0037D70D = 3659533 (ʮ����)
    cardNOOfPrivilege = &H37D70D
    ret = IntToByte(cardNOOfPrivilege, sendBuff, 8, 4)
    '20 10 01 01 ��ʼ����:  2010��01��01��   (�������2001��)
    sendBuff(8 + 4) = &H20
    sendBuff(8 + 5) = &H10
    sendBuff(8 + 6) = &H1
    sendBuff(8 + 7) = &H1
    '20 29 12 31 ��ֹ����:  2029��12��31��
    sendBuff(8 + 8) = &H20
    sendBuff(8 + 9) = &H29
    sendBuff(8 + 10) = &H12
    sendBuff(8 + 11) = &H31
    '01 ����ͨ�� һ���� (�Ե���, ˫��, ���ſ�������Ч)
    sendBuff(8 + 12) = &H1
    '01 ����ͨ�� ������ (��˫��, ���ſ�������Ч)
    sendBuff(8 + 13) = &H1  '�����ֹ2����, ��ֻҪ��Ϊ &H00
    '01 ����ͨ�� ������ (�����ſ�������Ч)
    sendBuff(8 + 14) = &H1
    '01 ����ͨ�� �ĺ��� (�����ſ�������Ч)
    sendBuff(8 + 15) = &H1

    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then

            success = 1
            '��ʱ ˢ����Ϊ= &H0037D70D = 3659533 (ʮ����)�Ŀ�, 1���ż̵�������.
            log ("1.11 Ȩ����ӻ��޸�     �ɹ�...")
        End If
    End If


    '1.12  Ȩ��ɾ��(����ɾ��)(���ܺ�: &H52) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H52
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    'Ҫɾ����Ȩ�޿���0D D7 37 00  = &H0037D70D = 3659533 (ʮ����)
    cardNOOfPrivilege = &H37D70D
    ret = IntToByte(cardNOOfPrivilege, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then
            success = 1
            '��ʱ ˢ����Ϊ= &H0037D70D = 3659533 (ʮ����)�Ŀ�, 1���ż̵������ᶯ��.
            log ("1.12 Ȩ��ɾ��(����ɾ��)     �ɹ�...")
        End If
    End If


    '1.13  Ȩ�����(ȫ�����)(���ܺ�: &H54) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H54
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '  ��ʶ(��ֹ������)    1   &H55 (�̶�)
    i = SpecialFlag
    ret = IntToByte(i, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff, 2000)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then
            success = 1
            '��ʱ��ճɹ�
            log ("1.13 Ȩ�����(ȫ�����)     �ɹ�...")
        End If
    End If


    '1.14  Ȩ��������ȡ(���ܺ�: &H58) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H58
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        privilegeCount = ByteToLong(recvBuff, 8, 4)
        log ("1.14 Ȩ��������ȡ   �ɹ�...")
    End If
    
    
     '�ٴ����Ϊ��ѯ����  1.11  Ȩ����ӻ��޸�(���ܺ�: &H50) **********************************************************************************
    '���ӿ���0D D7 37 00, ͨ����ǰ��������������
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H50
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '0D D7 37 00 Ҫ��ӻ��޸ĵ�Ȩ���еĿ��� = &H0037D70D = 3659533 (ʮ����)
    cardNOOfPrivilege = &H37D70D
    ret = IntToByte(cardNOOfPrivilege, sendBuff, 8, 4)
    '20 10 01 01 ��ʼ����:  2010��01��01��   (�������2001��)
    sendBuff(8 + 4) = &H20
    sendBuff(8 + 5) = &H10
    sendBuff(8 + 6) = &H1
    sendBuff(8 + 7) = &H1
    '20 29 12 31 ��ֹ����:  2029��12��31��
    sendBuff(8 + 8) = &H20
    sendBuff(8 + 9) = &H29
    sendBuff(8 + 10) = &H12
    sendBuff(8 + 11) = &H31
    '01 ����ͨ�� һ���� (�Ե���, ˫��, ���ſ�������Ч)
    sendBuff(8 + 12) = &H1
    '01 ����ͨ�� ������ (��˫��, ���ſ�������Ч)
    sendBuff(8 + 13) = &H1  '�����ֹ2����, ��ֻҪ��Ϊ &H00
    '01 ����ͨ�� ������ (�����ſ�������Ч)
    sendBuff(8 + 14) = &H1
    '01 ����ͨ�� �ĺ��� (�����ſ�������Ч)
    sendBuff(8 + 15) = &H1

    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then

            success = 1
            '��ʱ ˢ����Ϊ= &H0037D70D = 3659533 (ʮ����)�Ŀ�, 1���ż̵�������.
            log ("1.11 Ȩ����ӻ��޸�     �ɹ�...")
        End If
    End If
    

    '1.15  Ȩ�޲�ѯ(���ܺ�: &H5A) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H5A
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ' (�鿨��Ϊ 0D D7 37 00��Ȩ��)
    cardNOOfPrivilege = &H37D70D
    ret = IntToByte(cardNOOfPrivilege, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        cardNOOfPrivilegeGet = ByteToDouble(recvBuff, 8, 4)
        If (cardNOOfPrivilege = cardNOOfPrivilegeGet) Then
            log ("1.15     ��Ȩ����Ϣ...")
        Else
            log ("1.15      û��Ȩ����Ϣ: (���Ų���Ϊ0)")
        End If
        log ("1.15 Ȩ�޲�ѯ   �ɹ�...")
    End If
    '
    
     '1.16  ��ȡָ�������ŵ�Ȩ��[���ܺ�: 0x5C] **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H5C
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    i = 1 '������(��1��ʼ)
    ret = IntToByte(i, sendBuff, 8, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        cardNOOfPrivilegeGet = ByteToDouble(recvBuff, 8, 4) ' ByteToLong(recvBuff, 8, 4)
        If (4294967295# = cardNOOfPrivilegeGet) Then 'FFFFFFFF��Ӧ��4294967295
            log ("1.16      û��Ȩ����Ϣ: (Ȩ����ɾ��)")
        ElseIf (0 = cardNOOfPrivilegeGet) Then
            log ("1.16       û��Ȩ����Ϣ: (���Ų���Ϊ0)--��������֮��û��Ȩ����")
        Else
            log ("1.16      ��Ȩ����Ϣ...")
        End If
        log ("1.16  ��ȡָ�������ŵ�Ȩ��   �ɹ�...")
    End If
    '
    
    '1.17  �����ſ��Ʋ���(����/��ʱ) (���ܺ�: &H80) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H80
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '(����1���� ����  ������ʱ 3��)
    sendBuff(8 + 0) = &H1 '1����
    sendBuff(8 + 1) = &H3 '����
    sendBuff(8 + 2) = &H3 '������ʱ
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If ((sendBuff(8) = recvBuff(8)) And (sendBuff(9) = recvBuff(9)) And (sendBuff(10) = recvBuff(10))) Then
            '�ɹ�ʱ, ����ֵ������һ��
            success = 1
            log ("1.17 �����ſ��Ʋ���         �ɹ�...")
        End If
    End If



'1.21   Ȩ�ް���С����˳�����[���ܺ�: 0x56] ������Ȩ������1000, ����8�� **********************************************************************************
        '�˹���ʵ�� ��ȫ����ȫ��Ȩ��, �û��������֮ǰ��Ȩ��. ֻ�ǽ��ϴ���Ȩ��˳��ӵ�1�����ε����һ���ϴ����. �����;�жϵĻ�, ����ԭȨ��Ϊ��
        '����Ȩ�������³���50��, ����ʹ�ô�ָ��
        '��10000������Ϊ��, �˴��򻯵�����, ֱ������50001��ʼ��10000����. �û�������Ҫ��Ҫ�ϴ��Ŀ���������
        
        log ("1.21 Ȩ�ް���С����˳�����[���ܺ�: 0x56]��ʼ...")
        log ("       1����Ȩ��...")

        Dim cardCount As Integer
        cardCount = 10000
        '2015-06-09 20:20:20 ��������
        Dim cardArray(10000 - 1) As Long
        For i = 0 To cardCount - 1
            cardArray(i) = 50001 + i
        Next
        For i = 0 To cardCount - 1
            ret = arrayReset(sendBuff, WGPacketSize)
            sendBuff(0) = WGPacketType
            sendBuff(1) = &H56
            ret = IntToByte(controllerSN, sendBuff, 4, 4)
           
            cardNOOfPrivilege = cardArray(i)
            ret = DoubleToByte(cardNOOfPrivilege, sendBuff, 8, 4)
            '20 10 01 01 ��ʼ����:  2010��01��01��   (�������2001��)
            sendBuff(8 + 4) = &H20
            sendBuff(8 + 5) = &H10
            sendBuff(8 + 6) = &H1
            sendBuff(8 + 7) = &H1
            '20 29 12 31 ��ֹ����:  2029��12��31��
            sendBuff(8 + 8) = &H20
            sendBuff(8 + 9) = &H29
            sendBuff(8 + 10) = &H12
            sendBuff(8 + 11) = &H31
            '01 ����ͨ�� һ���� (�Ե���, ˫��, ���ſ�������Ч)
            sendBuff(8 + 12) = &H1
            '01 ����ͨ�� ������ (��˫��, ���ſ�������Ч)
            sendBuff(8 + 13) = &H1  '�����ֹ2����, ��ֻҪ��Ϊ &H00
            '01 ����ͨ�� ������ (�����ſ�������Ч)
            sendBuff(8 + 14) = &H1
            '01 ����ͨ�� �ĺ��� (�����ſ�������Ч)
            sendBuff(8 + 15) = &H1
        
            ret = IntToByte(cardCount, sendBuff, 32, 4)            '�ܵ�Ȩ����
            ret = IntToByte(i + 1, sendBuff, 35, 4)      '��ǰȨ�޵�����λ(��1��ʼ)
        
            ret = pktrun(sendBuff, recvBuff)
            success = 0
            If (ret = 1) Then
                getReceiveBuffData recvBuff
                If (recvBuff(8) = 1) Then
                    success = 1
                Else
                     If recvBuff(8) = &HE1 Then
                        log ("1.21Ȩ�ް���С����˳�����[���ܺ�: 0x56] =0xE1 ��ʾ����û�д�С��������...???")
                       
                    End If
                    success = 0
                    Exit For
                 End If
            Else
               log ("1.21Ȩ�ް���С����˳�����[���ܺ�: 0x56] ͨ�Ų���...???")
                Exit For
            End If
        Next
        If success = 1 Then
            log ("1.21Ȩ�ް���С����˳�����[���ܺ�: 0x56] �ɹ�...")
        Else
            log ("1.21Ȩ�ް���С����˳�����[���ܺ�: 0x56] ʧ��...????")
        End If
    '����ָ��  **********************************************************************************


    ' **********************************************************************************

    '����  **********************************************************************************

    If (ret = 1) Then
        log ("�������ܲ��� �ɹ�...")
    Else
        log ("�������ܲ��� ʧ��????...")
    End If
End Sub


'ControllerIP �����õĿ�����IP��ַ
'controllerSN �����õĿ��������к�
'watchServerIP   Ҫ���õķ�����IP
'watchServerPort Ҫ���õĶ˿�
Private Sub testWatchingServer(ByVal controllerIP As String, ByVal controllerSN As Long, ByVal watchServerIP As String, ByVal watchServerPort As Long)
    Dim sendBuff(63) As Byte    '���ݷ��ͻ�����(64�ֽ�)
    Dim recvBuff(63) As Byte     '���ݽ��ջ�����(64�ֽ�)

    Me.Winsock1.RemoteHost = controllerIP
    Me.Winsock1.RemotePort = ControllerPort '60000

    Dim ret As Integer
    Dim success As Integer

    '1.18  ���ý��շ�������IP�Ͷ˿� (���ܺ�: 0x90) **********************************************************************************
    '  ���շ�������IP: 192.168.168.101  (��ǰ����IP)
    '(��������ÿ�������������, ֻҪ�����շ�������IP��Ϊ0.0.0.0 ������)
    '���շ������Ķ˿�: 61005
    'ÿ��5�뷢��һ��: 05
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H90
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    '������IP: 192.168.168.101
    'sendBuff(8 + 0) = 192
    'sendBuff(8 + 1) = 168
    'sendBuff(8 + 2) = 168
    'sendBuff(8 + 3) = 101
    Dim Ar() As String
    Ar = Split(watchServerIP, ".", , vbTextCompare)
    If UBound(Ar) <> 4 - 1 Then

        log ("watchServerIP ��ַ������")
        Exit Sub
    End If
    sendBuff(8 + 0) = CInt(Ar(0))
    sendBuff(8 + 1) = CInt(Ar(1))
    sendBuff(8 + 2) = CInt(Ar(2))
    sendBuff(8 + 3) = CInt(Ar(3))
    '���շ������Ķ˿�: 61005
    sendBuff(8 + 4) = (watchServerPort And &HFF)
    sendBuff(8 + 5) = ((watchServerPort - (watchServerPort And &HFF)) / 256) And &HFF

    'ÿ��5�뷢��һ��: 05 (��ʱ�ϴ���Ϣ������Ϊ5�� (��������ʱÿ��5�뷢��һ��  ��ˢ��ʱ��������))
    sendBuff(8 + 6) = 5

    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        If (recvBuff(8) = 1) Then

            success = 1
            log ("1.18 ���ý��շ�������IP�Ͷ˿�   �ɹ�...")
        Else
            log ("1.18 ���ý��շ�������IP�Ͷ˿�   ʧ��????...")
            
        End If
    Else
        log ("1.18 ���ý��շ�������IP�Ͷ˿�   ʧ��????...")
    End If
    Sleep (1000) '��ʱһ�� �ٶ�ȡ
    '1.19  ��ȡ���շ�������IP�Ͷ˿� (���ܺ�: 0x92) **********************************************************************************
    ret = arrayReset(sendBuff, WGPacketSize)
    sendBuff(0) = WGPacketType
    sendBuff(1) = &H92
    ret = IntToByte(controllerSN, sendBuff, 4, 4)
    ret = pktrun(sendBuff, recvBuff)
    success = 0
    If (ret = 1) Then
        getReceiveBuffData recvBuff
        success = 1
        log ("1.19 ��ȡ���շ�������IP�Ͷ˿�   �ɹ�...")
    Else
        log ("1.19 ��ȡ���շ�������IP�Ͷ˿�   ʧ��????...")
    End If

End Sub


'������շ��������״̬
Private Sub WatchingServerRuning(ByVal watchServerIP As String, ByVal watchServerPort As Long)

    watchingrecordIndex = -1
     
    Me.WinsockServer.Bind watchServerPort  'ʹ�õ�ǰ���Ե�watchServerPort
        log ("������շ��������״̬....")
End Sub



Private Sub Form_Unload(Cancel As Integer)
    Me.Winsock1.Close
    Me.WinsockServer.Close
End Sub

'�������������ݴ���
Private Sub WinsockServer_DataArrival(ByVal bytesTotal As Long)
    Dim sn As Long
    If (bytesTotal > 0 And ((bytesTota Mod WGPacketSize) = 0)) Then
        '����Ч����
    Else
        Dim varlose As Object
        Me.WinsockServer.GetData (varlose) '��յ�
        Exit Sub
    End If

    Dim receivedByteCnt As Integer
    Dim watchingRecvBuffVar As Variant
    Dim watchingRecvBuff(63) As Byte    '��������� ��������

    receivedByteCnt = 0
    Do While (receivedByteCnt < bytesTotal)
        Me.WinsockServer.GetData watchingRecvBuffVar, vbArray + vbByte, WGPacketSize

        '�������, ���ܺ�, Ҫһ��
        Dim i As Integer
        For i = 0 To WGPacketSize - 1
            watchingRecvBuff(i) = watchingRecvBuffVar(i)
        Next i
        If (watchingRecvBuff(1) = &H20) Then
            sn = ByteToLong(watchingRecvBuff, 4, 4)

            log ("���յ����Կ�����SN = " & sn & " �����ݰ�..")

            Dim recordIndex As Long
            recordIndex = ByteToLong(watchingRecvBuff, 8, 4)
            If (recordIndex > watchingrecordIndex) Then
                watchingrecordIndex = recordIndex
               displayRecordInformation watchingRecvBuff
            End If
            
            Text1.SelLength = 1              '��ʾ���һ��
            Text1.SelStart = Len(Text1.Text) '��ʾ���һ��
        End If

        receivedByteCnt = receivedByteCnt + WGPacketSize
    Loop
End Sub


