from ast import While
from distutils.command.upload import upload
from http import server
import time
from speedtest import Speedtest
import threading
import smtplib
from email.mime.text import MIMEText

import mysql.connector

limit_speed = 150
google_id = 'corsa1rkr@gmail.com'
google_password = 'zwqnnzismokxcphp'
mail_receiver_id = 'wez____@naver.com'

db_host = 'db_host'
db_port = 'db_port'
db_id = 'db_id'
db_password = 'db_password'
db_name = 'speedtest'

def email_send(server_name, server_address, isp_info, ping, download_speed, upload_speed):
    s = smtplib.SMTP('smtp.gmail.com', 587)
    s.starttls()
    s.login(google_id, google_password)
    msg = MIMEText('인터넷 속도가 ' + str(limit_speed) + 'Mbit/s 이하로 측정되어 자동으로 발송된 메일 입니다.\n\n측정시간 : ' + time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + '\n대상 서버 : ' + server_name + ' (' + server_address +')\nISP 정보 : ' + isp_info + '\nPING : ' + ping + '\n다운로드 속도 : ' + download_speed + '\n업로드 속도 : ' + upload_speed)
    msg['Subject'] = '인터넷 속도 저하 감지 안내'
    msg['From'] = '인터넷 속도 모니터링'
    msg['To'] = mail_receiver_id
    s.sendmail(google_id, mail_receiver_id, msg.as_string())
    s.quit()
    print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 이메일을 전송 하였습니다.")
    
def speedtest_to_result():
    while True:
        try:
            mydb = mysql.connector.connect(
                host=db_id,
                port = db_port,
                user=db_id,
                passwd=db_password,
                database=db_name 
            )
            mc = mydb.cursor()
            servers = []
            threads = None
            sql = "UPDATE speedtest_refresh SET message=%s WHERE id=1;"
            val = ('측정중',)
            mc.execute(sql, val)
            mydb.commit()

            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 속도 테스트 진행중...")
            speed = Speedtest()
            speed.get_servers(servers)
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 응답시간을 기반으로 최적의 서버를 찾는 중입니다.")
            speed.get_best_server()
            # result = speed.results
            temp = str(speed.results.dict())
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 최적의 서버가 검색 되었습니다.")
            # print(speed.results.dict())
            server_name = temp.split('sponsor\': \'')[1].split('\'')[0]
            server_address = temp.split('name\': \'')[1].split('\'')[0]
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] HOST : " + server_name + " (" + server_address + ")")
            isp = temp.split('isp\': \'')[1].split('\'')[0]
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] ISP : " + isp)
            ping = temp.split('ping\': ')[1].split(',')[0]
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] PING : " + ping)
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 다운로드 속도를 측정 중입니다.")
            download_speed = speed.download() / 1000.0 / 1000.0
            str_download_speed = str(download_speed).split(".")[0] + " Mbit/s"
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 다운로드 속도 : " + str_download_speed)
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 업로드 속도를 측정 중입니다.")
            upload_speed = speed.upload() / 1000.0 / 1000.0
            str_upload_speed = str(upload_speed).split(".")[0] + " Mbit/s"
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 업로드 속도 : " + str_upload_speed)

            sql = "INSERT INTO test_info (time, server_name, server_address, isp_info, ping, download_speed, upload_speed) VALUES (%s, %s, %s, %s, %s, %s, %s)"
            val = (time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()), server_name, server_address, isp, ping, str_download_speed, str_upload_speed)
            mc.execute(sql, val)
            mydb.commit()
            sql = "UPDATE speedtest_refresh SET message=%s WHERE id=1;"
            val = ('',)
            mc.execute(sql, val)
            mydb.commit()

            if download_speed <= limit_speed:
                print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [WARN] 인터넷 속도가 " + str(limit_speed) + "Mbit/s 이하로 감지 되었습니다.")
                email_send(server_name, server_address, isp, ping, str_download_speed, str_upload_speed)
            # else:
            #     print("빠름")
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 다음 속도 측정을 대기중 입니다.")
            time.sleep(10800)
        except KeyboardInterrupt:
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 모니터링 종료됨")
            exit()
        except Exception as e:
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [WARN] 예외가 발생하였습니다.")
            print(e)

def speedcheck():
    try:
        global db_host
        global db_port
        global db_id
        global db_password
        global db_name
        mydb = mysql.connector.connect(
            host=db_host,
            port = db_port,
            user=db_id,
            passwd=db_password,
            database=db_name 
        )
        mc = mydb.cursor()
        servers = []
        threads = None
        sql = "UPDATE speedtest_refresh SET message=%s WHERE id=1;"
        val = ('측정중',)
        mc.execute(sql, val)
        mydb.commit()

        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 수동 새로고침 진행중...")
        speed = Speedtest()
        speed.get_servers(servers)
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 응답시간을 기반으로 최적의 서버를 찾는 중입니다.")
        speed.get_best_server()
        # result = speed.results
        temp = str(speed.results.dict())
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 최적의 서버가 검색 되었습니다.")
        # print(speed.results.dict())
        server_name = temp.split('sponsor\': \'')[1].split('\'')[0]
        server_address = temp.split('name\': \'')[1].split('\'')[0]
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] HOST : " + server_name + " (" + server_address + ")")
        isp = temp.split('isp\': \'')[1].split('\'')[0]
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] ISP : " + isp)
        ping = temp.split('ping\': ')[1].split(',')[0]
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] PING : " + ping)
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 다운로드 속도를 측정 중입니다.")
        download_speed = speed.download() / 1000.0 / 1000.0
        str_download_speed = str(download_speed).split(".")[0] + " Mbit/s"
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 다운로드 속도 : " + str_download_speed)
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 업로드 속도를 측정 중입니다.")
        upload_speed = speed.upload() / 1000.0 / 1000.0
        str_upload_speed = str(upload_speed).split(".")[0] + " Mbit/s"
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 업로드 속도 : " + str_upload_speed)

        sql = "INSERT INTO test_info (time, server_name, server_address, isp_info, ping, download_speed, upload_speed) VALUES (%s, %s, %s, %s, %s, %s, %s)"
        val = (time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()), server_name, server_address, isp, ping, str_download_speed, str_upload_speed)
        mc.execute(sql, val)
        mydb.commit()

        if download_speed <= limit_speed:
            print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [WARN] 인터넷 속도가 " + str(limit_speed) + "Mbit/s 이하로 감지 되었습니다.")
            email_send(server_name, server_address, isp, ping, str_download_speed, str_upload_speed)
        # else:
        #     print("빠름")
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 수동 새로고침이 종료되었습니다.")
        sql = "UPDATE speedtest_refresh SET message=%s WHERE id=1;"
        val = ('',)
        mc.execute(sql, val)
        mydb.commit()
    except KeyboardInterrupt:
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [INFO] 모니터링 종료됨")
        exit()
    except Exception as e:
        print(time.strftime("%Y-%m-%d %H:%M:%S", time.localtime()) + " [WARN] 예외가 발생하였습니다.")
        print(e)

def sql_command_check():
    global db_host
    global db_port
    global db_id
    global db_password
    global db_name
    mydb2 = mysql.connector.connect(
        host=db_host,
        port = db_port,
        user=db_id,
        passwd=db_password,
        database=db_name 
    )
    mc2 = mydb2.cursor()

    while True:
        sql2 = "SELECT * FROM speedtest_refresh"
        mc2.execute(sql2)
        data2 = mc2.fetchall()
        mydb2.commit()

        sql_command = data2[0][1]

        if sql_command == "refresh":
            sql2 = "UPDATE speedtest_refresh SET command=%s WHERE id=1;"
            val2 = ('',)
            mc2.execute(sql2, val2)
            mydb2.commit()
            speedcheck()

if __name__ == "__main__":
    thread1=threading.Thread(target=speedtest_to_result,args=())
    thread1.start()
    thread2=threading.Thread(target=sql_command_check,args=())
    thread2.start()