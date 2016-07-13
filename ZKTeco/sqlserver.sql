--Ա�����ڼ�¼�޸���־
CREATE TABLE CHECKEXACT (
        EXACTID INT IDENTITY(1,1) NOT NULL ,    --�޸���־ID
	USERID INT NULL DEFAULT 0,              --Ա��ID�� 
	CHECKTIME DATETIME NULL DEFAULT 0,      --ǩ��/ǩ��ʱ��
	CHECKTYPE VARCHAR (2) NULL DEFAULT 0,   --ԭ����ǩ��/ǩ�˱�־��I-ǩ����O-ǩ��
	ISADD SMALLINT NULL DEFAULT 0,          --�Ƿ������ļ�¼
	YUYIN VARCHAR (25) NULL ,               --�޸Ŀ��ڼ�¼��ԭ��
	ISMODIFY SMALLINT NULL DEFAULT 0,       --�Ƿ�������޸�ԭʼ��¼
	ISDELETE SMALLINT NULL DEFAULT 0,       --�Ƿ���ɾ���ļ�¼
	INCOUNT SMALLINT NULL DEFAULT 0,        --�Ƿ�ָ�
	ISCOUNT SMALLINT NULL DEFAULT 0,        --
	MODIFYBY VARCHAR (20) NULL,             --����Ա
	[DATE] DATETIME NULL,                   --����ʱ��          
        CONSTRAINT EXACTID PRIMARY KEY (EXACTID)
)
;

--ǩ��/ǩ�˼�¼��
CREATE TABLE CHECKINOUT (               
	USERID INT NOT NULL ,                              --Ա��ID��     
	CHECKTIME DATETIME NOT NULL DEFAULT GETDATE(),     --ǩ��/ǩ��ʱ��  
	CHECKTYPE VARCHAR (1) NULL DEFAULT 'I',            --ǩ��/ǩ�˱�־��I-ǩ����O-ǩ��
	VERIFYCODE INT NULL DEFAULT 0,                     --��֤��ʽ����256��������ʾǩ���ķ�ʽ: 0-���룻1-ָ�ƣ�2-��������256��ʾ�ü�¼�Ѿ���ȷ�ϣ����ڴ���ʱ�����д���
	SENSORID VARCHAR (5) NULL,                         --�ɼ����ݵĿ����ն�/�豸ID
        CONSTRAINT USERCHECKTIME PRIMARY KEY (USERID, CHECKTIME)
)
;

--���ű�
CREATE TABLE DEPARTMENTS (                                 
	DEPTID INT IDENTITY(1,1) NOT NULL ,      --����ID
	DEPTNAME VARCHAR (30) NULL ,             --��������
	SUPDEPTID INT NOT NULL DEFAULT 1,        --�ϼ����ŵ�ID              
        CONSTRAINT DEPTID PRIMARY KEY (DEPTID)
)
;

CREATE TABLE EXCNOTES (              
	USERID INT NULL ,
	ATTDATE DATETIME NULL ,
	NOTES VARCHAR (200) NULL
)  
;

--�ڼ��ձ�
CREATE TABLE HOLIDAYS (                            
	HOLIDAYID INT IDENTITY(1,1) NOT NULL ,   
	HOLIDAYNAME VARCHAR (20) NULL ,            
	HOLIDAYYEAR SMALLINT NULL ,                
	HOLIDAYMONTH SMALLINT NULL ,               
	HOLIDAYDAY SMALLINT NULL DEFAULT 1,                 
	STARTTIME DATETIME NULL ,                  
	DURATION SMALLINT NULL ,                   
	HOLIDAYTYPE SMALLINT NULL ,                
	XINBIE VARCHAR (4) NULL ,                     
	MINZU VARCHAR (50) NULL,                       
        CONSTRAINT HOLID PRIMARY KEY (HOLIDAYID)
)
;

--��α�
CREATE TABLE NUM_RUN (                              
	NUM_RUNID INT IDENTITY(1,1) NOT NULL ,      --���ID��
	OLDID INT NULL DEFAULT -1,                  --          
	NAME VARCHAR (30) NOT NULL ,                --�������
	STARTDATE DATETIME NULL DEFAULT '2000-1-1', --��������                  
	ENDDATE DATETIME NULL DEFAULT '2099-12-31', --����ʹ������                    
	CYLE SMALLINT NULL DEFAULT 1,               --���һ��ѭ��������         
	UNITS SMALLINT NULL DEFAULT 1,              --���ڵ�λ          
        CONSTRAINT NUMID PRIMARY KEY (NUM_RUNID)
)
;

--����Ű�ʱ�α�
CREATE TABLE NUM_RUN_DEIL (                      
	NUM_RUNID SMALLINT NOT NULL ,           --��ε�ID��     
	STARTTIME DATETIME NOT NULL ,           --��ʼʱ��     
	ENDTIME DATETIME NULL ,                 --����ʱ�� 
	SDAYS SMALLINT NOT NULL ,               --��ʼ����     
	EDAYS SMALLINT NULL ,                   --��������
        SCHCLASSID INT NULL DEFAULT -1,         --ʱ�����������
        CONSTRAINT NUMID2 PRIMARY KEY (NUM_RUNID, SDAYS, STARTTIME)
)
;

--����ԱȨ�����ñ�
CREATE TABLE SECURITYDETAILS (                 
	SECURITYDETAILID INT IDENTITY(1,1) NOT NULL ,
	USERID SMALLINT NULL ,
	DEPTID SMALLINT NULL ,
	SCHEDULE SMALLINT NULL ,
	USERINFO SMALLINT NULL ,
	ENROLLFINGERS SMALLINT NULL ,
	REPORTVIEW SMALLINT NULL ,
	REPORT VARCHAR (10) NULL,
        CONSTRAINT NAMEID2 PRIMARY KEY (SECURITYDETAILID)
)  
;

--�ְ��
CREATE TABLE SHIFT (                           
	SHIFTID INT IDENTITY(1,1) NOT NULL ,            --�ְ�ID��
	NAME VARCHAR (20) NULL ,                        --�ְ�����
	USHIFTID INT NULL DEFAULT -1,                   --  
	STARTDATE DATETIME NOT NULL DEFAULT '1900-1-1', --�����ְ������          
	ENDDATE DATETIME NULL DEFAULT '1900-12-31',     --����ʱ���ְ������            
	RUNNUM SMALLINT NULL DEFAULT 0,                 --���ְ��������İ���� 0<x<13
	SCH1 INT NULL DEFAULT 0,                        --�ְ�ĵ�һ����� 
	SCH2 INT NULL DEFAULT 0,                        --�ְ�ĵڶ������ 
	SCH3 INT NULL DEFAULT 0,                        --�ְ�ĵ��������
	SCH4 INT NULL DEFAULT 0,                        --�ְ�ĵ��ĸ����
	SCH5 INT NULL DEFAULT 0,                        --�ְ�ĵ�������
	SCH6 INT NULL DEFAULT 0,                        --�ְ�ĵ��������
	SCH7 INT NULL DEFAULT 0,                        --�ְ�ĵ��߸����
	SCH8 INT NULL DEFAULT 0,                        --�ְ�ĵڰ˸����
	SCH9 INT NULL DEFAULT 0,                        --�ְ�ĵھŸ����
	SCH10 INT NULL DEFAULT 0,                       --�ְ�ĵ�ʮ�����
	SCH11 INT NULL DEFAULT 0,                       --�ְ�ĵ�ʮһ�����
	SCH12 INT NULL DEFAULT 0,                       --�ְ�ĵ�ʮ�������
	CYCLE SMALLINT NULL DEFAULT 0,                  --�ְ�����
	UNITS SMALLINT NULL DEFAULT 0 ,                 --���ڵ�λ
        CONSTRAINT SHIFTS PRIMARY KEY (SHIFTID)
)  
;

--Ա���Ǽ�ָ�Ʊ�
CREATE TABLE TEMPLATE (                           
	TEMPLATEID INT IDENTITY(1,1) NOT NULL ,    --ָ��ID��
	USERID INT NOT NULL ,                      --Ա��ID��
	FINGERID INT NOT NULL ,                    --��ָID�ţ�0-9�ֱ��ʾ����Ĵָ������ʳָ����������������ָ������Сָ��
	TEMPLATE image NOT NULL ,                  --ָ��ģ��1
	TEMPLATE2 image NULL ,                     --ָ��ģ��2
	TEMPLATE3 image NULL ,                     --ָ��ģ��3
	BITMAPPICTURE image NULL ,                 --�Ǽǵ�ָ��ͼ��1
	BITMAPPICTURE2 image NULL ,                --�Ǽǵ�ָ��ͼ��2  
	BITMAPPICTURE3 image NULL ,                --�Ǽǵ�ָ��ͼ��3  
	BITMAPPICTURE4 image NULL ,                --�Ǽǵ�ָ��ͼ��4  
	USETYPE SMALLINT NULL ,                    --ָ��ģ����÷���Bit0��ʾ�������ݲ��ȶԣ�Bit1��ʾָ�Ʊȶ�ʱʹ�õ�ʶ������  
        CONSTRAINT TEMPLATED PRIMARY KEY (TEMPLATEID)
)
;

--Ա���Ű��
CREATE TABLE USER_OF_RUN (              
	USERID INT NOT NULL ,                           --Ա��ID��
	NUM_OF_RUN_ID INT not NULL ,                    --��λ��ְ�ID��
	STARTDATE DATETIME not NULL DEFAULT '1900-1-1', --���ð�ε�����         
	ENDDATE DATETIME not NULL DEFAULT '2099-12-31', --����ʹ�øð�ε�����           
	ISNOTOF_RUN INT NULL DEFAULT 0,                 --�Ƿ��ְ� 
	ORDER_RUN INT NULL ,                            --�ְ����ʼ���
        CONSTRAINT USER_ST_NUM PRIMARY KEY (USERID, NUM_OF_RUN_ID, STARTDATE, ENDDATE)
)
;

--Ա���������⣨���/��������
CREATE TABLE USER_SPEDAY (                    			
	USERID INT NOT NULL ,                                   --Ա��ID��
	STARTSPECDAY DATETIME NOT NULL DEFAULT '1900-1-1',      --��ʼ����    
	ENDSPECDAY DATETIME NULL DEFAULT '2099-12-31',          --��������  
	DATEID SMALLINT not NULL DEFAULT -1,                    --�������ͣ�999Ϊ������-1Ϊע��
	YUANYING VARCHAR (200) NULL ,                           --�����ԭ��
	[DATE] DATETIME NULL ,                                  --�Ǽ�/�����¼��ʱ�� 
        CONSTRAINT USER_SEP PRIMARY KEY (USERID, STARTSPECDAY, DATEID)
)
;

--Ա����ʱ�Ű��
CREATE TABLE USER_TEMP_SCH (                  
	USERID INT not NULL ,                     --Ա��ID��
	COMETIME DATETIME not NULL ,              --�ϰ�ʱ��
	LEAVETIME DATETIME not NULL ,             --�°�ʱ��  
        OVERTIME INT not NULL DEFAULT 0,           --��ʱ���м���Ӱ��ʱ�� 
	[TYPE] SMALLINT NULL DEFAULT 0,           --����       
	FLAG SMALLINT NULL DEFAULT 1,             --��־
        SCHCLASSID INT NULL DEFAULT -1,           --��ʱ������ʱ�����ID�ţ�-1��ʾ�Զ��б�
        CONSTRAINT USER_TEMP PRIMARY KEY (USERID, COMETIME, LEAVETIME)

)
;

--Ա����Ϣ��
CREATE TABLE USERINFO (                      
	USERID INT IDENTITY(1,1) NOT NULL ,     --Ա��ID��
	BADGENUMBER VARCHAR (12) NOT NULL ,     --���ں���    
	SSN VARCHAR (20) NULL ,                 --����֤/֤����
	NAME VARCHAR (20) NULL ,                --����
	GENDER VARCHAR (2) NULL ,               --�Ա�
	TITLE VARCHAR (20) NULL ,               --ְ��
	PAGER VARCHAR (20) NULL ,               --�ƶ��绰/������
	BIRTHDAY DATETIME NULL ,                --����
	HIREDDAY DATETIME NULL ,                --�μӹ�������
	STREET VARCHAR (40) NULL ,              --��ͥ��ַ
	CITY VARCHAR (2) NULL ,                 --�д���
	STATE VARCHAR (2) NULL ,                --ʡ����
	ZIP VARCHAR (12) NULL ,                 --�ʱ�
	OPHONE VARCHAR (20) NULL ,              --�칫�绰   
	FPHONE VARCHAR (20) NULL ,              --��ͥ�绰
	VERIFICATIONMETHOD SMALLINT NULL ,      --��֤��ʽ
	DEFAULTDEPTID SMALLINT NULL  DEFAULT 1, --��������ID��
	SECURITYFLAGS SMALLINT NULL ,           --����Ա��־
	ATT SMALLINT NOT NULL DEFAULT 1,        --������Ч
	INLATE SMALLINT NOT NULL DEFAULT 1,     --�Ƴٵ�               
	OUTEARLY SMALLINT NOT NULL DEFAULT 1,   --������                
	OVERTIME SMALLINT NOT NULL DEFAULT 1,   --�ƼӰ�                 
	SEP SMALLINT NOT NULL DEFAULT 1,        --             
	HOLIDAY SMALLINT NOT NULL DEFAULT 1,    --������Ϣ             
	MINZU VARCHAR (8) NULL ,                --����
	[PASSWORD] VARCHAR (20) NULL ,          --����
	LUNCHDURATION SMALLINT NOT NULL DEFAULT 1, --������
        MVerifyPass VARCHAR(10) NULL,           --������֤����
	PHOTO Image NULL,                       --��Ƭ  
        CONSTRAINT USERIDS PRIMARY KEY (USERID)
)
;


CREATE  UNIQUE  INDEX USERFINGER ON TEMPLATE(USERID, FINGERID)
;

CREATE  UNIQUE  INDEX HOLIDAYNAME ON HOLIDAYS(HOLIDAYNAME)
;

CREATE  INDEX DEPTNAME ON DEPARTMENTS(DEPTNAME)
;

CREATE  UNIQUE  INDEX EXCNOTE ON EXCNOTES(USERID, ATTDATE)
;

CREATE  UNIQUE  INDEX BADGENUMBER ON USERINFO(BADGENUMBER)
;

INSERT INTO DEPARTMENTS (DEPTNAME, SUPDEPTID) VALUES('�ܹ�˾',0)
;

--�����
Create Table LeaveClass(
  LeaveId INT Identity(1,1) not null primary key,  --����ID��
  LeaveName VARCHAR(20) not null,                  --��������
  MinUnit float not null default 1,                --��Сͳ�Ƶ�λ
  Unit smallint not null default 1,                --ͳ�Ƶ�λ
  RemaindProc smallint not null default 1,         --�������
  RemaindCount smallint not null default 1,        --ͳ��ʱ�ۼ�
  ReportSymbol varchar(4) not null default '-',    --�����еı�ʾ����
  Deduct float not null default 0,                 --
  Color int not null default 0,                    --��ʾ��ɫ
  Classify SMALLINT NOT null default 0)            --�������~bit7-�Ƿ����Ϊ���
;

--ͳ����Ŀ��
Create Table LeaveClass1(
  LeaveId INT Identity(999,1) not null primary key, --999-����
  LeaveName VARCHAR(20) not null,
  MinUnit float not null default 1,
  Unit smallint not null default 0,
  RemaindProc smallint not null default 2,
  RemaindCount smallint not null default 1,
  ReportSymbol varchar(4) not null default '_',
  Deduct float not null default 0,
  LeaveType SMALLINT not null default 0,             --bit0-�Ƿ�ͳ���bit1-�Ƿ��쳣����
  Color int not null default 0,
  Classify SMALLINT not null default 0,              --�������bit0-�����ʱ�η��ࣻbit1-���ڼ��շ���
  Calc text null)                                    --���㵥λ
;

--���ʱ��������ñ�
CREATE TABLE SchClass(
  schClassid INT identity(1,1) NOT NULL PRIMARY KEY, --ʱ�����ID��
  schName VARCHAR(20) NOT null,                      --ʱ��������� 
  StartTime datetime NOT NULL,                       --��ʼʱ��
  EndTime datetime NOT NULL,                         --����ʱ��
  LateMinutes int null,                              --�Ƴٵ�������
  EarlyMinutes int null,                             --�����˷����� 
  CheckIn int null default 1,                        --��ʱ���ϰ���Ҫǩ��
  CheckOut int null default 1,                       --��ʱ���°���Ҫǩ��
  CheckInTime1 datetime NULL,                        --��ʼǩ��ʱ��  
  CheckInTime2 datetime NULL,                        --����ǩ��ʱ��      
  CheckOutTime1 datetime NULL,                       --��ʼǩ��ʱ��   
  CheckOutTime2 datetime NULL,                       --����ǩ��ʱ��
  Color Int NULL default 16715535,                   --��ʾ��ɫ 
  AutoBind SMALLINT NULL DEFAULT 1)                  --   
;

--ϵͳ������
Create Table AttParam(
  PARANAME VARCHAR (20) NOT NULL Primary key,
  PARATYPE VARCHAR (2) NULL ,
  PARAVALUE VARCHAR(100) NOT NULL)
;
insert into LeaveClass(LeaveName, Unit, ReportSymbol, Color) 
  values('����', 1, 'B', 3398744);
insert into LeaveClass(LeaveName, Unit, ReportSymbol, Color) 
  values('�¼�', 1, 'S', 8421631);
insert into LeaveClass(LeaveName, Unit, ReportSymbol, Color) 
  values('̽�׼�', 1, 'T', 16744576);
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('����', 0.5, 3, 1, 1, 'G', 3, 'if(AttItem(LeaveType1)=999,AttItem(LeaveTime1),0)+if(AttItem(LeaveType2)=999,AttItem(LeaveTime2),0)+if(AttItem(LeaveType3)=999,AttItem(LeaveTime3),0)+if(AttItem(LeaveType4)=999,AttItem(LeaveTime4),0)+if(AttItem(LeaveType5)=999,AttItem(LeaveTime5),0)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType)
  values('����', 0.5, 3, 1, 0, ' ', 3);
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('�ٵ�', 10, 2, 2, 1, '>', 3, 'AttItem(minLater)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('����', 10, 2, 2, 1, '<', 3, 'AttItem(minEarly)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('���', 1, 1, 1, 1, '��', 3, 
  'if((AttItem(LeaveType1)>0) and (AttItem(LeaveType1)<999),AttItem(LeaveTime1),0)+if((AttItem(LeaveType2)>0) and (AttItem(LeaveType2)<999),AttItem(LeaveTime2),0)+if((AttItem(LeaveType3)>0) and (AttItem(LeaveType3)<999),AttItem(LeaveTime3),0)+if((AttItem(LeaveType4)>0) and (AttItem(LeaveType4)<999),AttItem(LeaveTime4),0)+if((AttItem(LeaveType5)>0) and (AttItem(LeaveType5)<999),AttItem(LeaveTime5),0)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('����', 0.5, 3, 1, 0, '��', 3, 'AttItem(MinAbsent)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('�Ӱ�', 1, 1, 1, 1, '+', 3, 'AttItem(MinOverTime)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('���ռӰ�', 1, 1, 0, 1, '=', 0, 'if(HolidayId(1)=1, AttItem(MinOverTime),0)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType)
  values('��Ϣ��', 0.5, 3, 2, 1, '-', 2);
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('δǩ��', 1, 4, 2, 1, '[', 2, 
  'If(AttItem(CheckIn)=null,If(AttItem(OnDuty)=null,0,if(((AttItem(LeaveStart1)=null) or (AttItem(LeaveStart1)>AttItem(OnDuty))) and AttItem(DutyOn),1,0)),0)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType, Calc)
  values('δǩ��', 1, 4, 2, 1, ']', 2, 
  'If(AttItem(CheckOut)=null,If(AttItem(OffDuty)=null,0,if((AttItem(LeaveEnd1)=null) or (AttItem(LeaveEnd1)<AttItem(OffDuty)),if((AttItem(LeaveEnd2)=null) or (AttItem(LeaveEnd2)<AttItem(OffDuty)),if(((AttItem(LeaveEnd3)=null) or (AttItem(LeaveEnd3)<AttItem(OffDuty))) and AttItem(DutyOff),1,0),0),0)),0)');
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType)
  values('���δǩ��', 1, 4, 2, 1, '{', 3);
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType)
  values('���δǩ��', 1, 4, 2, 1, '}', 3);
insert into LeaveClass1(LeaveName, MinUnit, Unit, RemaindProc,
  RemaindCount, ReportSymbol, LeaveType)
  values('���', 1, 1, 2, 1, 'L', 3);

insert into AttParam(ParaName,ParaValue) values('MinsEarly',5);
insert into AttParam(ParaName,ParaValue) values('MinsLate',10);
insert into AttParam(ParaName,ParaValue) values('MinsNoBreakIn',60);
insert into AttParam(ParaName,ParaValue) values('MinsNoBreakOut',60);
insert into AttParam(ParaName,ParaValue) values('MinsNoIn',60);
insert into AttParam(ParaName,ParaValue) values('MinsNoLeave',60);
insert into AttParam(ParaName,ParaValue) values('MinsNotOverTime',60);
insert into AttParam(ParaName,ParaValue) values('MinsWorkDay',420);
insert into AttParam(ParaName,ParaValue) values('NoBreakIn',1012);
insert into AttParam(ParaName,ParaValue) values('NoBreakOut',1012);
insert into AttParam(ParaName,ParaValue) values('NoIn',1001);
insert into AttParam(ParaName,ParaValue) values('NoLeave',1002);
insert into AttParam(ParaName,ParaValue) values('OutOverTime',0);
insert into AttParam(ParaName,ParaValue) values('TwoDay',0);
insert into AttParam(ParaName,ParaValue) values('CheckInColor',16777151);
insert into AttParam(ParaName,ParaValue) values('CheckOutColor',12910591);
insert into AttParam(ParaName,ParaValue) values('DBVersion',167);