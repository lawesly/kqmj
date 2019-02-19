{/**
* $Id: WWGBasicDelphiTest 2015-04-29 23:09:17 karl CSN 陈绍宁 $
*
* 门禁控制器 短报文协议 测试案例
* V1.3 版本  2013-11-09 10:11:19
*            基本功能:  查询控制器状态 
*                       读取日期时间
*                       设置日期时间
*                       获取指定索引号的记录
*                       设置已读取过的记录索引号
*                       获取已读取过的记录索引号
*                       远程开门
*                       权限添加或修改
*                       权限删除(单个删除)
*                       权限清空(全部清掉)
*                       权限总数读取
*                       权限查询
*                       设置门控制参数(在线/延时)
*                       读取门控制参数(在线/延时)

*                       设置接收服务器的IP和端口
*                       读取接收服务器的IP和端口
*                       
*
*                       接收服务器的实现 (在61005端口接收数据) -- 此项功能 一定要注意防火墙设置 必须是允许接收数据的.
* V2.5 版本  2015-04-29 20:41:30 采用 V6.56驱动版本 型号由$19改为$17
*/
}

unit WGBasicDelphiTest;

interface

uses
  Windows, Messages, SysUtils, Variants, Classes, Graphics, Controls, Forms,
  Dialogs, StdCtrls, IdUDPServer, IdBaseComponent, IdComponent, IdUDPBase,
  IdUDPClient,  WGPacketShort,
  IdSocketHandle;

type
  TByteDynArray         = array of Byte;
  TForm1 = class(TForm)
    IdUDPClient1: TIdUDPClient;
    Button1: TButton;
    IdUDPServer1: TIdUDPServer;
    Memo1: TMemo;
    Label1: TLabel;
    Label2: TLabel;
    Label3: TLabel;
    Edit1: TEdit;
    Edit2: TEdit;
    Edit3: TEdit;
    Edit4: TEdit;
    Label4: TLabel;
    procedure Button1Click(Sender: TObject);
    procedure  testBasicFunction( ip: PChar; const controllerSN: Cardinal);
    procedure  testWatchingServer( ip: PChar; const controllerSN: Cardinal;   watchServerIP: PChar; const watchServerPort: Cardinal);
       //接收服务器设置
    procedure  WatchingServerRuning(  watchServerIP: PChar; const watchServerPort: Cardinal);
    procedure  log(info:PChar);
    procedure  logStr(const info:String);
    function   pktrun (var ASendBuff: array of Byte; var BReceiveBuff: array of Byte):integer;
    procedure UDPServerUDPRead(Sender: TObject; AData: TStream; ABinding: TIdSocketHandle); //接收服务器 处理数据
    procedure displayRecordInformation(var recvBuff: array of Byte);    //2015-06-11 19:39:36
  private
    { Private declarations }
  public
    { Public declarations }
  end;

var
  Form1: TForm1;
  sendSequenceId: Cardinal;      //流水号
  watchingRecordIndex : Cardinal;   //服务器监控时处理的记录索引号

Const

        RecordDetails: array[0..179] of string =
        (
//记录原因 (类型中 SwipePass 表示通过; SwipeNOPass表示禁止通过; ValidEvent 有效事件(如按钮 门磁 超级密码开门); Warn 报警事件)
//代码  类型   英文描述  中文描述
'1','SwipePass','Swipe','刷卡开门',
'2','SwipePass','Swipe Close','刷卡关',
'3','SwipePass','Swipe Open','刷卡开',
'4','SwipePass','Swipe Limited Times','刷卡开门(带限次)',
'5','SwipeNOPass','Denied Access: PC Control','刷卡禁止通过: 电脑控制',
'6','SwipeNOPass','Denied Access: No PRIVILEGE','刷卡禁止通过: 没有权限',
'7','SwipeNOPass','Denied Access: Wrong PASSWORD','刷卡禁止通过: 密码不对',
'8','SwipeNOPass','Denied Access: AntiBack','刷卡禁止通过: 反潜回',
'9','SwipeNOPass','Denied Access: More Cards','刷卡禁止通过: 多卡',
'10','SwipeNOPass','Denied Access: First Card Open','刷卡禁止通过: 首卡',
'11','SwipeNOPass','Denied Access: Door Set NC','刷卡禁止通过: 门为常闭',
'12','SwipeNOPass','Denied Access: InterLock','刷卡禁止通过: 互锁',
'13','SwipeNOPass','Denied Access: Limited Times','刷卡禁止通过: 受刷卡次数限制',
'14','SwipeNOPass','Denied Access: Limited Person Indoor','刷卡禁止通过: 门内人数限制',
'15','SwipeNOPass','Denied Access: Invalid Timezone','刷卡禁止通过: 卡过期或不在有效时段',
'16','SwipeNOPass','Denied Access: In Order','刷卡禁止通过: 按顺序进出限制',
'17','SwipeNOPass','Denied Access: SWIPE GAP LIMIT','刷卡禁止通过: 刷卡间隔约束',
'18','SwipeNOPass','Denied Access','刷卡禁止通过: 原因不明',
'19','SwipeNOPass','Denied Access: Limited Times','刷卡禁止通过: 刷卡次数限制',
'20','ValidEvent','Push Button','按钮开门',
'21','ValidEvent','Push Button Open','按钮开',
'22','ValidEvent','Push Button Close','按钮关',
'23','ValidEvent','Door Open','门打开[门磁信号]',
'24','ValidEvent','Door Closed','门关闭[门磁信号]',
'25','ValidEvent','Super Password Open Door','超级密码开门',
'26','ValidEvent','Super Password Open','超级密码开',
'27','ValidEvent','Super Password Close','超级密码关',
'28','Warn','Controller Power On','控制器上电',
'29','Warn','Controller Reset','控制器复位',
'30','Warn','Push Button Invalid: Disable','按钮不开门: 按钮禁用',
'31','Warn','Push Button Invalid: Forced Lock','按钮不开门: 强制关门',
'32','Warn','Push Button Invalid: Not On Line','按钮不开门: 门不在线',
'33','Warn','Push Button Invalid: InterLock','按钮不开门: 互锁',
'34','Warn','Threat','胁迫报警',
'35','Warn','Threat Open','胁迫报警开',
'36','Warn','Threat Close','胁迫报警关',
'37','Warn','Open too long','门长时间未关报警[合法开门后]',
'38','Warn','Forced Open','强行闯入报警',
'39','Warn','Fire','火警',
'40','Warn','Forced Close','强制关门',
'41','Warn','Guard Against Theft','防盗报警',
'42','Warn','7*24Hour Zone','烟雾煤气温度报警',
'43','Warn','Emergency Call','紧急呼救报警',
'44','RemoteOpen','Remote Open Door','操作员远程开门',
'45','RemoteOpen','Remote Open Door By USB Reader','发卡器确定发出的远程开门'
        );
implementation

{$R *.dfm}

procedure TForm1.Button1Click(Sender: TObject);
var
controllerSN, watchServerPort : Cardinal;
controllerIP, watchServerIP: PChar;
begin
	//本案例未作搜索控制器  及 设置IP的工作  (直接由IP设置工具来完成)
	//本案例中测试说明
	//控制器SN  = 229999901
	//控制器IP  = 192.168.168.123
	//电脑  IP  = 192.168.168.101
	//用于作为接收服务器的IP (本电脑IP 192.168.168.101), 接收服务器端口 (61005)
  controllerSN := StrToInt(Edit1.Text); // Cardinal(Edit1.Text);    //229999901
  controllerIP := PChar(Edit2.Text); //'192.168.168.123';
	watchServerIP := PChar(Edit3.Text); //'192.168.168.101';
	watchServerPort := StrToInt(Edit4.Text); //61005;

  log ('基本功能测试 开始');
	testBasicFunction(controllerIP,controllerSN); //基本功能测试
  testWatchingServer( controllerIP, controllerSN, watchServerIP, watchServerPort);
           //接收服务器设置
  WatchingServerRuning ( watchServerIP, watchServerPort);
end;




procedure arrayReset(var data: array of Byte);
var
i:integer;
begin
for i:=0 to Length(data)-1 do data[i]:= 0;
end;

procedure TForm1.log(info:PChar);
begin
     Memo1.Lines.Add(info);
end;

procedure TForm1.logStr(const info:String);
begin
     Memo1.Lines.Add(info);
end;


function  TForm1.pktrun (var ASendBuff: array of Byte; var BReceiveBuff: array of Byte):integer;
var
  tries: integer;
  ret: integer;
begin
   inc(sendSequenceId);
   CopyMemory(@(ASendBuff[40]),@sendSequenceId,4);   //序号
   tries :=3;
   ret := -1;
   while(tries >0) do
      begin
         IdUDPClient1.SendBuffer(ASendBuff  ,WGPacketShort.WGPacketSize);
         if (IdUDPClient1.ReceiveBuffer(BReceiveBuff,WGPacketShort.WGPacketSize)
         = WGPacketShort.WGPacketSize) then
         //检查类型, 功能号, 流水号要一致
         if ((ASendBuff[0] = BReceiveBuff[0])
           and (ASendBuff[1]= BReceiveBuff[1])
           and (ASendBuff[40]= BReceiveBuff[40]) and (ASendBuff[41]= BReceiveBuff[41])
           and (ASendBuff[42]= BReceiveBuff[42]) and (ASendBuff[43]= BReceiveBuff[43])) then
          begin
            ret :=1;
            break;
         end;
         tries := tries -1;
   end;
   pktrun := ret;
end;
function GetHex(val:integer):byte; //获取Hex值, 主要用于日期时间格式
begin
  GetHex :=  ((val mod 10) + (((val -(val mod 10)) div 10) mod  10) *16);
end;



function getReasonDetailChinese(Reason:integer):string;
begin
if (Reason > 45) then
  getReasonDetailChinese :=  ''
else   if (Reason <= 0) then
  getReasonDetailChinese :=  ''
else
  getReasonDetailChinese := RecordDetails[(Reason - 1) * 4 + 3]; //中文信息
end;

function getReasonDetailEnglish(Reason:integer):string;
begin
if (Reason > 45) then
  getReasonDetailEnglish :=  ''
else   if (Reason <= 0) then
  getReasonDetailEnglish :=  ''
else
  getReasonDetailEnglish := RecordDetails[(Reason - 1) * 4 + 2]; //英文信息
end;

procedure TForm1.displayRecordInformation(var recvBuff: array of Byte);
var
    //与刷卡记录相关变量
    recordIndex, recordType, recordValid, recordDoorNO, recordInOrOut,
    recordCardNO, reason:
               Cardinal;
begin

		//8-11	最后一条记录的索引号
		//(=0表示没有记录)	4	0x00000000
   recordIndex :=0;
		CopyMemory(@recordIndex, @(recvBuff[8]),4);

		//12	记录类型
		//0=无记录
		//1=刷卡记录
		//2=门磁,按钮, 设备启动, 远程开门记录
		//3=报警记录	1
	  recordType := recvBuff[12];

		//13	有效性(0 表示不通过, 1表示通过)	1
	  recordValid := recvBuff[13];

		//14	门号(1,2,3,4)	1
	  recordDoorNO := recvBuff[14];

		//15	进门/出门(1表示进门, 2表示出门)	1	0x01
	 recordInOrOut := recvBuff[15];

		//16-19	卡号(类型是刷卡记录时)
		//或编号(其他类型记录)	4
	  recordCardNO := 0;
		CopyMemory(@recordCardNO, @(recvBuff[16]),4);

		//20-26	刷卡时间:
		//年月日时分秒 (采用BCD码)见设置时间部分的说明
//    logStr((format('  记录时间: %02X%02X-%02X-%02X %02X:%02X:%02X',
//			[recvBuff[20],recvBuff[21],recvBuff[22],recvBuff[23],recvBuff[24],recvBuff[25],recvBuff[26]])));

		//27	记录原因代码(可以查 “刷卡记录说明.xls”文件的ReasonNO)
		//处理复杂信息才用	1
    reason := recvBuff[27];


    //0=无记录
            //1=刷卡记录
            //2=门磁,按钮, 设备启动, 远程开门记录
            //3=报警记录	1	
            //0xFF=表示指定索引位的记录已被覆盖掉了.  请使用索引0, 取回最早一条记录的索引值
            if (recordType = 0) then
            begin
                logStr(format('索引位=%u  无记录', [recordIndex]));
            end
            else if (recordType = $ff) then
            begin
                logStr(' 指定索引位的记录已被覆盖掉了,请使用索引0, 取回最早一条记录的索引值');
            end
            else if (recordType = 1) then //2015-06-10 08:49:31 显示记录类型为卡号的数据
            begin
                //卡号
                logStr(format('索引位=%u  ', [recordIndex]));
                logStr(format('  卡号 = %u', [recordCardNO]));
                logStr(format('  门号 = %u', [recordDoorNO]));
                if (recordInOrOut = 1) then
                  logStr(format('  进出 = %s', ['进门']))
                else
                  logStr(format('  进出 = %s', ['出门']));

                if (recordValid = 1) then
                   logStr(format('  有效 = %s', ['通过']))
                else
                   logStr(format('  有效 = %s', ['禁止']));
                   logStr((format('  时间 = %02X%02X-%02X-%02X %02X:%02X:%02X',
			[recvBuff[20],recvBuff[21],recvBuff[22],recvBuff[23],recvBuff[24],recvBuff[25],recvBuff[26]])));

                logStr(format('  描述 = %s', [getReasonDetailChinese(reason)]));
            end
            else if (recordType = 2) then
            begin
                //其他处理
                //门磁,按钮, 设备启动, 远程开门记录
                logStr(format('索引位=%u  非刷卡记录', [recordIndex]));
                logStr(format('  编号 = %u', [recordCardNO]));
                logStr(format('  门号 = %u', [recordDoorNO]));
                logStr((format('  时间 = %02X%02X-%02X-%02X %02X:%02X:%02X',
			[recvBuff[20],recvBuff[21],recvBuff[22],recvBuff[23],recvBuff[24],recvBuff[25],recvBuff[26]])));
               logStr(format('  描述 = %s', [getReasonDetailChinese(reason)]));
            end
            else if (recordType = 3) then
            begin
                //其他处理
                //报警记录
                logStr(format('索引位=%u  报警记录', [recordIndex]));
                logStr(format('  编号 = %u', [recordCardNO]));
                logStr(format('  门号 = %u', [recordDoorNO]));
                logStr((format('  时间 = %02X%02X-%02X-%02X %02X:%02X:%02X',
			[recvBuff[20],recvBuff[21],recvBuff[22],recvBuff[23],recvBuff[24],recvBuff[25],recvBuff[26]])));
                logStr(format('  描述 = %s', [getReasonDetailChinese(reason)]));
            end;
end;

procedure  TForm1.testBasicFunction( ip: PChar; const controllerSN: Cardinal);
var
    sendBuff: array[0..(WGPacketShort.WGPacketSize-1)] of Byte;
    recvBuff: array[0..(WGPacketShort.WGPacketSize-1)] of Byte;
    ret: integer;
    i: integer;
    tries: integer;
    success: integer;

    //与刷卡记录相关变量
    recordIndex, recordType, recordValid, recordDoorNO, recordInOrOut,
    recordCardNO, reason, errCode, sequenceId, relayStatus, otherInputStatus:
               Cardinal;

    doorStatus, pbStatus: array[0..3] of integer;
    pcTime, recordTime, controllerTime: TDatetime;
    Year, Month, Day,  Hour, Min, Sec, MSec: Word;

    recordIndexToGet: Cardinal;
    recordIndexGot: Cardinal;
		recordIndexToGetStart: Cardinal;
		recordIndexValidGet: Cardinal;

    doorNO: byte;
    cardNOOfPrivilege, privilegeCount : Cardinal;

    recordIndexGotToRead: Cardinal;

    cardArray: array[0..9999] of Cardinal;
    cardCount: Cardinal;
            j: integer;
begin
    IdUDPClient1.Host := ip;
    IdUDPClient1.Port := WGPacketShort.ControllerPort;
    IdUDPClient1.ReceiveTimeout := 400;

    logStr(format('  控制器SN: %u',[controllerSN]));

    //1.4	查询控制器状态[功能号: 0x20](实时监控用) **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $20;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
	  Begin
		//读取信息成功...
		success :=1;
		log('1.4 查询控制器状态 成功...');
    //	  	最后一条记录的信息
    displayRecordInformation(recvBuff);

		//	其他信息
		//int doorStatus[4];
		//28	1号门门磁(0表示关上, 1表示打开)	1	0x00
		doorStatus[1-1] := recvBuff[28];
		//29	2号门门磁(0表示关上, 1表示打开)	1	0x00
		doorStatus[2-1] := recvBuff[29];
		//30	3号门门磁(0表示关上, 1表示打开)	1	0x00
		doorStatus[3-1] := recvBuff[30];
		//31	4号门门磁(0表示关上, 1表示打开)	1	0x00
		doorStatus[4-1] := recvBuff[31];

		//int pbStatus[4];
		//32	1号门按钮(0表示松开, 1表示按下)	1	0x00
		pbStatus[1-1] := recvBuff[32];
		//33	2号门按钮(0表示松开, 1表示按下)	1	0x00
		pbStatus[2-1] := recvBuff[33];
		//34	3号门按钮(0表示松开, 1表示按下)	1	0x00
		pbStatus[3-1] := recvBuff[34];
		//35	4号门按钮(0表示松开, 1表示按下)	1	0x00
		pbStatus[4-1] := recvBuff[35];
		//36	故障号
		//等于0 无故障
		//不等于0, 有故障(先重设时间, 如果还有问题, 则要返厂家维护)	1
		errCode := recvBuff[36];

		//37	控制器当前时间
		//时	1	0x21
		//38	分	1	0x30
		//39	秒	1	0x58

		//40-43	流水号	4
		//long long  sequenceId=0;
		CopyMemory(@sequenceId, @(recvBuff[40]),4);

		//48
		//特殊信息1(依据实际使用中返回)
		//键盘按键信息	1
		//49	继电器状态	1
		 relayStatus := recvBuff[49];

		//50	门磁状态的8-15bit位[火警/强制锁门]
		//Bit0  强制锁门
		//Bit1  火警
		otherInputStatus := recvBuff[50];
		if ((otherInputStatus and 1) > 0) then log('强制锁门');
		if ((otherInputStatus and 2) > 0)  then  log('火警');


		//51	V5.46版本支持 控制器当前年	1	0x13
		//52	V5.46版本支持 月	1	0x06
		//53	V5.46版本支持 日	1	0x22
   //控制器当前时间
    logStr(format('  控制器时间: 20%02X-%02X-%02X %02X:%02X:%02X',[recvBuff[51],recvBuff[52],recvBuff[53],recvBuff[37],recvBuff[38],recvBuff[39]]));
  end
	else
	begin
		log('1.4 查询控制器状态 失败?????...');
	   exit; //	return -1;
	end;

	//1.5	读取日期时间(功能号: 0x32) **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $32;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
	  Begin
		success :=1;
		log('1.5 读取日期时间 成功...');
     logStr(format('  控制器时间: %02X%02X-%02X-%02X %02X:%02X:%02X',[recvBuff[8],recvBuff[9],recvBuff[10],recvBuff[11],recvBuff[12],recvBuff[13],recvBuff[14]]));
    end;

	//1.6	设置日期时间[功能号: 0x30] **********************************************************************************
	//按电脑当前时间校准控制器.....
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $30;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    pcTime := Now();
    DecodeDate(pcTime, Year, Month, Day);
    DecodeTime(pcTime, Hour, Min, Sec, MSec);

    sendBuff[8 + 0] := GetHex((( Year -( Year mod 100)) div 100));
  	sendBuff[8 + 1] := GetHex(Year mod 100);
	  sendBuff[8 + 2] := GetHex(Month);
	  sendBuff[8 + 3] := GetHex(Day);
	  sendBuff[8 + 4] := GetHex(Hour);
	  sendBuff[8 + 5] := GetHex(Min);
	  sendBuff[8 + 6] := GetHex(Sec);

    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
     if (CompareMem(@(sendBuff[8]),@(recvBuff[8]),7)) then
  	  Begin
 	 	  success :=1;
		  log('1.6	设置日期时间 成功...');
      end;

 	//1.7	获取指定索引号的记录[功能号: 0xB0] **********************************************************************************
	//(取索引号 0x00000001的记录)
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $B0;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	//	(特殊
	//如果=0, 则取回最早一条记录信息
	//如果=0xffffffff则取回最后一条记录的信息)
	//记录索引号正常情况下是顺序递增的, 最大可达0xffffff = 16,777,215 (超过1千万) . 由于存储空间有限, 控制器上只会保留最近的20万个记录. 当索引号超过20万后, 旧的索引号位的记录就会被覆盖, 所以这时查询这些索引号的记录, 返回的记录类型将是0xff, 表示不存在了.
	recordIndexToGet :=1;
	CopyMemory(@(sendBuff[8 + 0]), @recordIndexToGet, 4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
   	  Begin
 	 	  success :=1;
		  log('1.7 获取索引为1号记录的信息 成功...');
		//	  	索引为1号记录的信息		
 		    displayRecordInformation(recvBuff);

     end;

	//. 发出报文 (取最早的一条记录 通过索引号 0x00000000) [此指令适合于 刷卡记录超过20万时环境下使用]
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $B0;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	//如果=0, 则取回最早一条记录信息
	recordIndexToGet :=0;
	CopyMemory(@(sendBuff[8 + 0]), @recordIndexToGet, 4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
   	  Begin
 	 	  success :=1;
		  log('1.7 获取最早一条记录的信息 成功...');
		//	  	最早一条记录的信息
 		    displayRecordInformation(recvBuff);

     end;

     	//发出报文 (取最新的一条记录 通过索引 0xffffffff)
   arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $B0;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	//如果=0xffffffff, 则取回最新一条记录信息
	recordIndexToGet :=$ffffffff;
	CopyMemory(@(sendBuff[8 + 0]), @recordIndexToGet, 4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
   	  Begin
 	 	  success :=1;
		  log('1.7 获取最新记录的信息 成功...');
		//	  	最新记录的信息
         displayRecordInformation(recvBuff);
     end;

  //          //1.8	设置已读取过的记录索引号[功能号: 0xB2] **********************************************************************************
  // arrayReset(sendBuff);
  //  sendBuff[0] :=  WGPacketShort.WGPacketType;
  //  sendBuff[1] :=  $B2;
  //  CopyMemory(@(sendBuff[4]),@controllerSN,4);
  //  // (设为已读取过的记录索引号为5)
  //  recordIndexGot :=6;
  //  CopyMemory(@(sendBuff[8 + 0]), @recordIndexGot, 4);
  //  //12	标识(防止误设置)	1	0x55 [固定]
  //i := WGPacketShort.SpecialFlag;
  //  CopyMemory(@(sendBuff[8 + 4]), @i, 4);
  //   ret :=  pktrun(sendBuff, recvBuff);
  //  success :=0;
  //  if (ret = 1)  then
  //    Begin
  //        success :=1;
  //        log('1.8 设置已读取过的记录索引号 成功...');
  //   end;

  //  //1.9	获取已读取过的记录索引号[功能号: 0xB4] **********************************************************************************
  //  arrayReset(sendBuff);
  //  sendBuff[0] :=  WGPacketShort.WGPacketType;
  //  sendBuff[1] :=  $B4;
  //  CopyMemory(@(sendBuff[4]),@controllerSN,4);
  //   ret :=  pktrun(sendBuff, recvBuff);
  //  success :=0;
  //  if (ret = 1)  then
  //    Begin
  //        log('1.9 获取已读取过的记录索引号 成功...');
  //      CopyMemory( @recordIndexGot,@(recvBuff[8 + 0]), 4);
  //        success :=1;
  //   end;

	//1.9	提取记录操作
	//1. 通过 0xB4指令 获取已读取过的记录索引号 recordIndex
	//2. 通过 0xB0指令 获取指定索引号的记录  从recordIndex + 1开始提取记录， 直到记录为空为止
	//3. 通过 0xB2指令 设置已读取过的记录索引号  设置的值为最后读取到的刷卡记录索引号
	//经过上面三个步骤， 整个提取记录的操作完成
    log('1.9 提取记录操作	 开始...');
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $B4;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	  Begin
		  log('开始提取记录 ...');
 	    CopyMemory( @recordIndexGot,@(recvBuff[8 + 0]), 4);
      recordIndexToGetStart := recordIndexGot + 1;
		  recordIndexValidGet := 0;

      arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $B0;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
     i:=0;
      Repeat
       CopyMemory(@(sendBuff[8]), @recordIndexToGetStart, 4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
      if (ret = 1) then
      begin
      success :=1;
      	//12	记录类型
				//0=无记录
				//1=刷卡记录
				//2=门磁,按钮, 设备启动, 远程开门记录
				//3=报警记录	1	
				//0xFF=表示指定索引位的记录已被覆盖掉了.  请使用索引0, 取回最早一条记录的索引值
				recordType := recvBuff[12];
				if (recordType = 0) then break; //没有更多记录
				if (recordType = $ff) then
				begin
          //取最早一条记录的索引位
          arrayReset(sendBuff);
          sendBuff[0] :=  WGPacketShort.WGPacketType;
          sendBuff[1] :=  $B0;
           CopyMemory(@(sendBuff[4]),@controllerSN,4);
	        //如果=0, 则取回最早一条记录信息
	        recordIndexToGet :=0;
	        CopyMemory(@(sendBuff[8 + 0]), @recordIndexToGet, 4);
          ret :=  pktrun(sendBuff, recvBuff);
  	     success :=0;
  	     if (ret >0)  then
   	     Begin
 	 	      success :=1;
		      log('1.7 获取最早一条记录的信息 成功...');
          CopyMemory( @recordIndexGotToRead,@(recvBuff[8 + 0]), 4);
          recordIndexToGetStart := recordIndexGotToRead;
         end
         else
         begin
					success := 0;  //此索引号无效  重新设置索引值
					break;
         end;
				end;
				recordIndexValidGet := recordIndexToGetStart;
				//.......对收到的记录作存储处理
        displayRecordInformation(recvBuff);
				//*****
				//###############
        inc(recordIndexToGetStart);
        inc(i);
      end
      else break;
    until (i > 200000);

     if (success >0) then
         arrayReset(sendBuff);
         sendBuff[0] :=  WGPacketShort.WGPacketType;
         sendBuff[1] :=  $B2;
        CopyMemory(@(sendBuff[4]),@controllerSN,4);
			//通过 0xB2指令 设置已读取过的记录索引号  设置的值为最后读取到的刷卡记录索引号
	recordIndexGot :=recordIndexValidGet;
	CopyMemory(@(sendBuff[8 + 0]), @recordIndexGot, 4);
	//12	标识(防止误设置)	1	0x55 [固定]
  i := WGPacketShort.SpecialFlag;
	CopyMemory(@(sendBuff[8 + 4]), @i, 4);
     ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
         	if (recvBuff[8] = 1)   then
  	  Begin
      //完全提取成功....
 	 	  success :=1;
		  log('1.9 完全提取成功	  成功...');
     end;

     end;

	//1.10	远程开门[功能号: 0x40] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $40;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    doorNO :=1;
    sendBuff[8] := doorNO;
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
 			//有效开门.....
		log('1.10 远程开门	 成功...');
    end;

	//1.11	权限添加或修改[功能号: 0x50] **********************************************************************************
	//增加卡号0D D7 37 00, 通过当前控制器的所有门
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $50;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
 	//0D D7 37 00 要添加或修改的权限中的卡号 = 0x0037D70D = 3659533 (十进制)
   cardNOOfPrivilege := $0037D70D;
     CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
   	//20 10 01 01 起始日期:  2010年01月01日   (必须大于2001年)
	sendBuff[8 +4] := $20;
	sendBuff[8 +5] := $10;
	sendBuff[8 +6] := $01;
	sendBuff[8 +7] := $01;
	//20 29 12 31 截止日期:  2029年12月31日
	sendBuff[8 +8] := $20;
	sendBuff[8 +9] := $29;
	sendBuff[8 +10] := $12;
	sendBuff[8 +11] := $31;
	//01 允许通过 一号门 [对单门, 双门, 四门控制器有效]
	sendBuff[8 +12] := $01;
	//01 允许通过 二号门 [对双门, 四门控制器有效]
	sendBuff[8 +13] := $01;  //如果禁止2号门, 则只要设为 0x00
	//01 允许通过 三号门 [对四门控制器有效]
	sendBuff[8 +14] := $01;
	//01 允许通过 四号门 [对四门控制器有效]
	sendBuff[8 +15] := $01;

    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
 			//这时 刷卡号为= 0x0037D70D = 3659533 (十进制)的卡, 1号门继电器动作.
		log('1.11 权限添加或修改	 成功...');
    end;

 	//1.12	权限删除(单个删除)[功能号: 0x52] **********************************************************************************
     arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $52;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	//要删除的权限卡号0D D7 37 00  = 0x0037D70D = 3659533 (十进制)
   cardNOOfPrivilege := $0037D70D;
     CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
 			//这时 刷卡号为= 0x0037D70D = 3659533 (十进制)的卡, 1号门继电器不会动作.
		log('1.12 权限删除(单个删除)	 成功...');
    end;

    //1.13	权限清空(全部清掉)[功能号: 0x54] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $54;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
   	//	标识(防止误设置)	1	0x55 [固定]
    i := WGPacketShort.SpecialFlag;
   	CopyMemory(@(sendBuff[8]), @i, 4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
 			//这时清空成功
		log('1.13 权限清空(全部清掉)	 成功...');
    end;

    //1.14	权限总数读取[功能号: 0x58] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $58;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
    Begin
		success :=1;
    CopyMemory(@privilegeCount, @(recvBuff[8]),4);
 			//这时清空成功
		log('1.14 权限总数读取	 成功...');
    end;


    //再次添加为查询操作   1.11	权限添加或修改[功能号: 0x50] **********************************************************************************
	//增加卡号0D D7 37 00, 通过当前控制器的所有门
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $50;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
 	//0D D7 37 00 要添加或修改的权限中的卡号 = 0x0037D70D = 3659533 (十进制)
   cardNOOfPrivilege := $0037D70D;
     CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
   	//20 10 01 01 起始日期:  2010年01月01日   (必须大于2001年)
	sendBuff[8 +4] := $20;
	sendBuff[8 +5] := $10;
	sendBuff[8 +6] := $01;
	sendBuff[8 +7] := $01;
	//20 29 12 31 截止日期:  2029年12月31日
	sendBuff[8 +8] := $20;
	sendBuff[8 +9] := $29;
	sendBuff[8 +10] := $12;
	sendBuff[8 +11] := $31;
	//01 允许通过 一号门 [对单门, 双门, 四门控制器有效]
	sendBuff[8 +12] := $01;
	//01 允许通过 二号门 [对双门, 四门控制器有效]
	sendBuff[8 +13] := $01;  //如果禁止2号门, 则只要设为 0x00
	//01 允许通过 三号门 [对四门控制器有效]
	sendBuff[8 +14] := $01;
	//01 允许通过 四号门 [对四门控制器有效]
	sendBuff[8 +15] := $01;

    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
 			//这时 刷卡号为= 0x0037D70D = 3659533 (十进制)的卡, 1号门继电器动作.
		log('1.11 权限添加或修改	 成功...');
    end;

    	//1.15	权限查询[功能号: 0x5A] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $5A;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	 // (查卡号为 0D D7 37 00的权限)
    cardNOOfPrivilege := $0037D70D;
    CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
    Begin
		success :=1;
    		if (CompareMem(@cardNOOfPrivilege,@(recvBuff[8]),4)) then log('1.15     有权限信息...')
        else log('1.15      没有权限时: (卡号部分为0)');
		log('1.15 权限查询	 成功...');
    end;

        	//1.16  获取指定索引号的权限[功能号: 0x5C] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $5C;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);

    cardNOOfPrivilege := $1; // '索引号(从1开始)
    CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
    Begin
		success :=1;
    CopyMemory(@cardNOOfPrivilege,@(recvBuff[8]),4);
        if ( cardNOOfPrivilege = $ffffffff)  then   log('1.16      没有权限信息: (权限已删除)')
        else if  ( cardNOOfPrivilege = $0)  then  log('1.16       没有权限信息: (卡号部分为0)--此索引号之后没有权限了')
        else log('1.16      有权限信息...');
		log('1.16  获取指定索引号的权限   成功...');
    end;

    	//1.17	设置门控制参数(在线/延时) [功能号: 0x80] **********************************************************************************
     arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $80;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	  //(设置2号门 在线  开门延时 3秒)
	  sendBuff[8 +0] := $02; //2号门
	  sendBuff[8 +1] := $03; //在线
	  sendBuff[8 +2] := $03; //开门延时
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
   		if (CompareMem(@(sendBuff[8]),@(recvBuff[8]),3)) then
       Begin
			  //成功时, 返回值与设置一致
		    success :=1;
		    log('1.17 设置门控制参数	 	 成功...');
       end;



    //1.21	权限按从小到大顺序添加[功能号: 0x56] 适用于权限数过1000, 少于8万 **********************************************************************************
    //此功能实现 完全更新全部权限, 用户不用清空之前的权限. 只是将上传的权限顺序从第1个依次到最后一个上传完成. 如果中途中断的话, 仍以原权限为主
    //建议权限数更新超过50个, 即可使用此指令

    log('1.21	权限按从小到大顺序添加[功能号: 0x56]	开始...');
    log('       1万条权限...');

    //以10000个卡号为例, 此处简化的排序, 直接是以50001开始的10000个卡. 用户按照需要将要上传的卡号排序存放
    cardCount := 10000;  //2015-06-09 20:20:20 卡总数量
    for i:= 0 to  cardCount do
       cardArray[i] := 50001+i;

    for i:= 0 to cardCount do
    begin
               arrayReset(sendBuff);
               sendBuff[0] :=  WGPacketShort.WGPacketType;
               sendBuff[1] :=  $56;
               CopyMemory(@(sendBuff[4]),@controllerSN,4);
 	             cardNOOfPrivilege := cardArray[i];
               CopyMemory(@(sendBuff[8]),@cardNOOfPrivilege,4);
                 	//20 10 01 01 起始日期:  2010年01月01日   (必须大于2001年)
              	sendBuff[8 +4] := $20;
              	sendBuff[8 +5] := $10;
              	sendBuff[8 +6] := $01;
              	sendBuff[8 +7] := $01;
              	//20 29 12 31 截止日期:  2029年12月31日
              	sendBuff[8 +8] := $20;
              	sendBuff[8 +9] := $29;
              	sendBuff[8 +10] := $12;
              	sendBuff[8 +11] := $31;
              	//01 允许通过 一号门 [对单门, 双门, 四门控制器有效]
              	sendBuff[8 +12] := $01;
              	//01 允许通过 二号门 [对双门, 四门控制器有效]
              	sendBuff[8 +13] := $01;  //如果禁止2号门, 则只要设为 0x00
              	//01 允许通过 三号门 [对四门控制器有效]
              	sendBuff[8 +14] := $01;
              	//01 允许通过 四号门 [对四门控制器有效]
              	sendBuff[8 +15] := $01;
                CopyMemory(@(sendBuff[32]),@cardCount,4);  //总的权限数
                
                j:=i+1;
                CopyMemory(@(sendBuff[35]),@j,4);   //当前权限的索引位(从1开始)
                
                ret :=  pktrun(sendBuff, recvBuff);
                success :=0;
                if (ret = 1)  then
                begin
                	  if (recvBuff[8] = 1)  then
              	    Begin
              		  success :=1;
                    end;
                    if (recvBuff[8] = $E1)  then
              	    Begin
              		  log('1.21	权限按从小到大顺序添加[功能号: 0x56]	 =0xE1 表示卡号没有从小到大排序...???');
                                      success := 0;
                                      break;
                    end;
                end
                else
                    break;
     end;


     if (success = 1) then
                log('1.21	权限按从小到大顺序添加[功能号: 0x56]	 成功...')
     else
                log('1.21	权限按从小到大顺序添加[功能号: 0x56]	 失败...????');

	//其他指令  **********************************************************************************


	// **********************************************************************************

	//结束  **********************************************************************************

  if (ret = 1) then log('基本功能测试 成功...')
     else  log('基本功能测试 失败????...');
end;

function split(s,s1:string):TStringList;
begin
Result:=TStringList.Create;
while Pos(s1,s)>0 do
begin
Result.Add(Copy(s,1,Pos(s1,s)-1));
Delete(s,1,Pos(s1,s));
end;
Result.Add(s);
end;


//ControllerIP 被设置的控制器IP地址
//controllerSN 被设置的控制器序列号
//watchServerIP   要设置的服务器IP
//watchServerPort 要设置的端口
procedure  TFORM1.testWatchingServer( ip: PChar; const controllerSN: Cardinal;   watchServerIP: PChar; const watchServerPort: Cardinal);
        //接收服务器设置
var
    sendBuff: array[0..(WGPacketShort.WGPacketSize-1)] of Byte;
    recvBuff: array[0..(WGPacketShort.WGPacketSize-1)] of Byte;
    ret: integer;
    i: integer;
    tries: integer;
    success: integer;
    Ar:   TStringList;
    str1: string;
begin
    IdUDPClient1.Host := ip;
    IdUDPClient1.Port := WGPacketShort.ControllerPort;
    IdUDPClient1.ReceiveTimeout := 400;

	//1.18	设置接收服务器的IP和端口 [功能号: 0x90] **********************************************************************************
	//	接收服务器的IP: 192.168.168.101  [当前电脑IP]
	//(如果不想让控制器发出数据, 只要将接收服务器的IP设为0.0.0.0 就行了)
	//接收服务器的端口: 61005
	//每隔5秒发送一次: 05
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $90;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
	//服务器IP: 192.168.168.101
	//sendBuff[8 + 0] = 192;
	//sendBuff[8 + 1] = 168;
	//sendBuff[8 + 2] = 168;
	//sendBuff[8 + 3] = 101;
  Ar   :=   split(watchServerIP,   '.');
  if   Ar.Count   <>   4   then
  begin
   log('watchServerIP 地址不合理');
   Exit;
  end;
  sendBuff[8 + 0] := StrToInt(Ar[0]);
	sendBuff[8 + 1] := StrToInt(Ar[1]);
	sendBuff[8 + 2] := StrToInt(Ar[2]);
	sendBuff[8 + 3] := StrToInt(Ar[3]);
  	//接收服务器的端口: 61005
	sendBuff[8 + 4] := (watchServerPort and $ff);
	sendBuff[8 + 5] := (watchServerPort shr 8) and $ff;

  	//每隔5秒发送一次: 05 (定时上传信息的周期为5秒 [正常运行时每隔5秒发送一次  有刷卡时立即发送])
	sendBuff[8 + 6] := 5;

    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
  	if (recvBuff[8] = 1)  then
	  Begin
		success :=1;
		log('1.18 设置接收服务器的IP和端口 	 成功...');
    end;

	//1.19	读取接收服务器的IP和端口 [功能号: 0x92] **********************************************************************************
    arrayReset(sendBuff);
    sendBuff[0] :=  WGPacketShort.WGPacketType;
    sendBuff[1] :=  $92;
    CopyMemory(@(sendBuff[4]),@controllerSN,4);
    ret :=  pktrun(sendBuff, recvBuff);
  	success :=0;
  	if (ret = 1)  then
	  Begin
		success :=1;
		log('1.19 读取接收服务器的IP和端口 	 成功...');
    end;
end;

procedure  TFORM1.WatchingServerRuning(  watchServerIP: PChar; const watchServerPort: Cardinal);
begin
    IdUDPServer1.Bindings.add.Port := watchServerPort;
    IdUDPServer1.OnUDPRead := UDPServerUDPRead;
    IdUDPServer1.Active := True;
  log('进入接收服务器监控状态....');

end;

//接收到数据的处理
procedure TFORM1.UDPServerUDPRead(Sender: TObject; AData: TStream; ABinding: TIdSocketHandle);
var
  recvBuff: array[0..(WGPacketShort.WGPacketSize-1)] of Byte;
  recordType,sn,recordIndex, recordCardNO: Cardinal;
begin
 if (AData.Size = WGPacketShort.WGPacketSize ) then
 begin
   AData.Read(recvBuff, AData.Size);
   if  (recvBuff[1]= $20) then
   begin
		CopyMemory(@sn, @(recvBuff[4]),4);
	  logStr(format('接收到来自控制器SN = %d 的数据包..', [sn]));

   // logStr(format('%s:%d>', [ABinding.PeerIP, ABinding.PeerPort ]));

    recordIndex :=0;
		CopyMemory(@recordIndex, @(recvBuff[8]),4);
    if (recordIndex > watchingrecordIndex) then
    begin
	     watchingrecordIndex := recordIndex;
       displayRecordInformation(recvBuff);
    end;
 end;
end;
end;
end.
