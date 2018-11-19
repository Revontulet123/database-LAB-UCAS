#coding:utf-8
#放到train-2016-10文件夹里，和all-stations.csv一个目录
#2015K8009908020
import glob
import csv
import os
import string
from itertools import islice
#初步wash
def cleantherow(listrow):
    newrow = []
    #清除空格
    for i in range(len(listrow)):
        newrow.append(listrow[i].lstrip())
    #清除“分”
    newrow[4] = newrow[4].replace("分","")
    #分割 座位
    yzrz = newrow[7].split('/')
    if len(yzrz)==2:
        newrow.extend(yzrz)
    if len(yzrz)==1:
        newrow.extend([0,0])
    newrow.pop(7);
    yzrz = newrow[7].split('/')
    if len(yzrz)==3:
        newrow.extend(yzrz)
    if len(yzrz)==1:
        newrow.extend([0,0,0])
    newrow.pop(7);
    yzrz = newrow[7].split('/')
    if len(yzrz)==2:
        newrow.extend(yzrz)
    if len(yzrz)==1:
        newrow.extend([0,0])
    newrow.pop(7);
    #清除-
    for i in range(len(newrow)):
        if newrow[i]=='-' or newrow[i]== '' or newrow[i]== '0':
            if i == 2:
                newrow[2]=newrow[3]
            elif i == 3:
                newrow[3]=newrow[2]
            else:
                newrow[i]='0';
    for i in range(7,14,1):
        newrow[i] = string.atof(newrow[i]);
    #写入train_name
    (filepath,tempfilename) = os.path.split(filename);
    (shotname,extension) = os.path.splitext(tempfilename);
    newrow.insert(0,shotname)
    return newrow
#写SeatCount
def wseat_row(listrow,date,wrow3):
    newrow = []
    newrow.append(listrow[0])  #TrainID varchar(5)
    newrow.append(listrow[1])  #StationNum integer
    newrow.append(date) #StartDate
    if listrow[1]=='1':
        for i in range(1,8,1):
            newrow.append(0)
        return newrow
    for i in range(1,8,1):    # 7 counts
        if wrow3[i]!=0:
            newrow.append(5)
        else:
            newrow.append(0)
    return newrow

#写StationCity
fallstation = open('all-stations.csv','r')
fcity = open('StationCity.csv','a')
reader_allstation = csv.reader(fallstation)
writer_allstation = csv.writer(fcity)
data = []
for srow in reader_allstation:
    nsrow = []
    nsrow.append(string.atoi(srow[0].lstrip()))  #StationID integer
    nsrow.append(srow[1].lstrip()) #StationName varchar(20)
    nsrow.append(srow[2].lstrip()) #CityName varchar(20)
    data.append(nsrow)
    writer_allstation.writerow(nsrow);
#写Stations
def wstation_row(listrow):
    newrow = []
    newrow.append(listrow[0])                           #TrainID varchar(5)
    newrow.append(string.atoi(listrow[1]))              #StationNum integer
    for kkrow in data:
        if cmp(listrow[2],kkrow[1])==0:
            newrow.append(kkrow[0]) #StationID integer
            break
    newrow.append(listrow[2])                 #StationName varchar(20)
#newrow.append(string.atoi(listrow[7]))    #mile integer
#时间
    arrivelist = listrow[3].split(':')
    departlist = listrow[4].split(':')
    arrivemin = string.atoi(arrivelist[0])*60 + string.atoi(arrivelist[1])
    departmin = string.atoi(departlist[0])*60 + string.atoi(departlist[1])
    if ( arrivemin - string.atoi(listrow[6]) ) < 0:
        arrivemin = arrivemin + 1440
        departmin = departmin + 1440
        newrow.append(1)        #Gap integer
    elif (departmin-string.atoi(listrow[5])) < 0:
        departmin = departmin + 1440
        newrow.append(1)        #Gap integer
    else:
        newrow.append(0)        #Gap integer
    newrow.append(listrow[3])   #ArriveTime Time
    newrow.append(listrow[4])   #StartTime Time
    newrow.append(arrivemin)    #ArriveMin integer
    newrow.append(departmin)    #StartMin integer
    for i in range(8,15,1):     #7 counts
        newrow.append(listrow[i])
    flag = 0;
    for i in range(8,15,1):
        if listrow[i]==0:
            flag+=1;
    if (flag == 7 and listrow[1]!= '1' ):
        newrow.append(1)        #ForbidFlag integer
    else:
        newrow.append(0)
    return newrow

def wtrain_row(newrow,wrow3):
    for i in range(8,15,1):     #7 counts
        if newrow[i]!=0:
            wrow3[i-8] = 1
    return wrow3

#写seat,train,station三个表
csvx_list = glob.glob('*/*.csv')
fseat = open('SeatCount.csv','a')
fstation = open('Stations.csv','a')
ftrain = open('Trains.csv','a')
writeseat = csv.writer(fseat)
writestation = csv.writer(fstation)
writetrain = csv.writer(ftrain)
for filename in csvx_list:
    with open(filename,"r") as f:
        fr = csv.reader(f)
        wrow3 = [0,0,0,0,0,0,0]
        rowdata = []
        for row in islice(fr, 1, None):
            clearrow = []
            clearrow = cleantherow(row); #处理原行
            rowdata.append(clearrow)
            #写表
            wrow2 = []
            wrow2 = wstation_row(clearrow);
            writestation.writerow(wrow2);
            #special
            wrow3 = wtrain_row(clearrow,wrow3);
        #train表只一个文件只写一行
        wrow3.insert(0,clearrow[0])
        writetrain.writerow(wrow3);
        for roww in rowdata:
            wrow1 = []
            date = "2018/11/23"
            wrow1 = wseat_row(roww,date,wrow3);
            writeseat.writerow(wrow1);
            date = "2018/11/24"
            wrow1 = wseat_row(roww,date,wrow3);
            writeseat.writerow(wrow1);
            date = "2018/11/25"
            wrow1 = wseat_row(roww,date,wrow3);
            writeseat.writerow(wrow1);
fseat.close()
fstation.close()
ftrain.close()
fallstation.close()
fcity.close()