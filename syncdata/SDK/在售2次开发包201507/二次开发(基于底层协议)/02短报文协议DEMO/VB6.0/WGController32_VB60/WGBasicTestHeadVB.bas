Attribute VB_Name = "Module1"
'2014-09-20 18:43:19 �½�WG VB����
'������ʱ��Ҫ���õĺ��� , ͬʱ�����ڼ�ʱ����
Public Declare Function GetTickCount Lib "kernel32" () As Long
Public Declare Sub Sleep Lib "kernel32" (ByVal dwMilliseconds As Long)

'Sub timeDelay(ByVal DTms As Long)   '��msΪ��λ
'    Dim T As Long
'    T = GetTickCount()
'    Do
'        DoEvents()
'    Loop Until GetTickCount - T >= DT
'End Sub


'���鸴λΪ0
Public Function arrayReset(ByRef arrbyte() As Byte, ByVal length As Integer) As Integer
    Dim i As Integer
    For i = 0 To length - 1
        arrbyte(i) = 0
    Next i
    arrayReset = 1
End Function

'����ת���ֽ� (4�ֽ�)
Public Function IntToByte(ByVal value As Long, ByRef arrbyte() As Byte, ByVal start As Integer, ByVal length As Integer)
    Dim i As Integer
    Dim val As Long
    Dim validLen As Integer
    validLen = length
    val = value
    For i = 0 To validLen - 1
        If (value = &HFFFFFFFF) Then
            arrbyte(i + start) = &HFF
        Else
            arrbyte(i + start) = val Mod 256&
            val = (val - (val Mod 256&)) / 256&
        End If
    Next i
    IntToByte = 1
End Function

'����ת���ֽ� (8�ֽ�)
Public Function DoubleToByte(ByVal value As Double, ByRef arrbyte() As Byte, ByVal start As Integer, ByVal length As Integer)
    Dim i As Integer
    Dim val As Double
    Dim validLen As Integer
    validLen = length
    val = value
    For i = 0 To validLen - 1
        If (value = &HFFFFFFFF) Then
            arrbyte(i + start) = &HFF
        Else
            arrbyte(i + start) = val Mod 256&
            val = (val - (val Mod 256&)) / 256&
        End If
    Next i
    DoubleToByte = 1
End Function

'�ֽ�ת������(4�ֽ�)
Public Function ByteToLong(ByRef arrbyte() As Byte, ByVal start As Integer, ByVal length As Integer) As Long
    Dim i As Integer
    Dim val As Long
    Dim validLen As Integer
    validLen = length
    val = 0
    For i = validLen - 1 To 0 Step -1
        val = val * 256&
        val = val + arrbyte(i + start)
    Next i
    ByteToLong = val
End Function

'�ֽ�ת������(8�ֽ�)
Public Function ByteToDouble(ByRef arrbyte() As Byte, ByVal start As Integer, ByVal length As Integer) As Double
    Dim i As Integer
    Dim val As Double
    Dim validLen As Integer
    validLen = length
    val = 0
    For i = validLen - 1 To 0 Step -1
        val = val * 256&
        val = val + arrbyte(i + start)
    Next i
    ByteToDouble = val
End Function

Public Function GetFromBCD(ByVal val As Integer) As Byte '��ȡHexֵ, ��Ҫ��������ʱ���ʽ
    GetFromBCD = ((val - (val Mod 16)) / 16) * 10 + (val Mod 16)
End Function


'��������ʱ���ʽת��ΪWINDOWS ��ʱ���ʽ
Public Function getMsDate(ByVal yearH, ByVal yearL, ByVal month, ByVal day, ByVal hour, ByVal minute, ByVal second) As Date
    Dim i As Long, strTime As String
    i = GetFromBCD(yearH)
    i = i * 100
    i = i + GetFromBCD(yearL)
    strTime = Trim(Str$(i)) & "-"    '��
    i = GetFromBCD(month)
    strTime = strTime & Trim(Str$(i)) & "-"       '��
    i = GetFromBCD(day)
    strTime = strTime & Trim(Str$(i)) & " "       '��
    i = GetFromBCD(hour)
    strTime = strTime & Trim(Str$(i)) & ":"       'ʱ
    i = GetFromBCD(minute)
    strTime = strTime & Trim(Str$(i)) & ":"       '��
    i = GetFromBCD(second)
    strTime = strTime & Trim(Str$(i))        '��
    If Not IsDate(strTime) Then strTime = "2000-1-1 0:0:0" '�������ʱ���ʽ,��ȱʡ2000��1��1�� 0ʱ0��0�� ��ֵ
    getMsDate = strTime
End Function

'��ȡBCDֵ, ��Ҫ��������ʱ���ʽ
Public Function GetHex(ByVal val As Integer) As Byte
    GetHex = ((val Mod 10) + (((val - (val Mod 10)) / 10) Mod 10) * 16)
End Function
