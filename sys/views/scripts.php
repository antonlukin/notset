<div class="header">
	<h1>Бесполезные скрипты</h1>
	<h2></h2>
</div>

<div class="content">
	<h2 class="content-subhead"></h2>
	<p>
<code class="no">#!/bin/bash
# ddos_protect v.1.0.7

RECORDS=1500
DOMAIN_LIST="lifehacker.ru"
BLOCK_TIME=5 #in minutes
IP_WHITELIST=""
RESOLVE_IP=true # resolve IP
BLOCK_ENABLE=false # true/false - enable/disable add firewall rule.
email="" # email="" - not send email

LOGFILE=/var/log/ddos-table.log
LOGFILE2=/var/log/ddos-table-history.log
IPT="/sbin/iptables"

for D in $DOMAIN_LIST
do

    TMP_LOG=/tmp/ddos-$D-acc-temp.log
    NGINX_LOG=/srv/www/$D/logs/$D-acc
	
    #echo $NGINX_LOG

    if [ ! -f $NGINX_LOG ]; then
        echo "Log ($NGINX_LOG) not found."
        exit 1
    fi

    if [ ! -f $LOGFILE ]; then
        touch $LOGFILE
    fi

    tail -10000 $NGINX_LOG | awk '{print $1}' | sort | uniq -c | sort -n | awk -v x=$RECORDS ' $1 > x {print $2} ' > $TMP_LOG

    while read line
        do
            IP=$line
            wh=0
            for I in $IP_WHITELIST
                do
                    if [ $I = $IP ]; then
                        wh=1
                    fi
                done
            if [ $wh = 1 ]; then
               echo $IP in whitelist. `date` >> $LOGFILE2
            else
                        DOUBLE=`/sbin/iptables-save | grep "\-j DROP" | grep "$IP"`
                        if [ -n "$DOUBLE" ]; then
                            echo "$IP exist in DROP rule. `date`" >> $LOGFILE2
                        else
                            PTR=""
                            if [ $RESOLVE_IP == true ]; then
                                /usr/bin/host $IP >/dev/null
                                if [ $? -eq 0 ]
                                then
                                    PTR=" (`/usr/bin/host $IP | awk '{ print $5 }'`)"
                                else
                                    PTR=""
                                fi
                            fi
                            if [ $BLOCK_ENABLE == true ]; then
                                echo "$IP blocked $BLOCK_TIME minutes `date` ($D) Unblock = `date --date="$BLOCK_TIME minute" +%s`" >> $LOGFILE
                                echo "`date`. $IP $PTR blocked $BLOCK_TIME minutes. ($D)" >> $LOGFILE2
                                if [ $email  != "" ]; then
                                 echo "$IP $PTR blocked $BLOCK_TIME minutes `date` ($D)" | /bin/mail -s "$IP$PTR Blocked" $email
                                fi
                                $IPT -A INPUT -s $IP -j DROP
                            else
                                echo "`date`. (TEST MODE) $IP$PTR. Match $RECORDS records. ($D)" >> $LOGFILE2
                                if [ $email  != "" ]; then
                                echo "(TEST MODE) $IP $PTR. Match $RECORDS records. `date` ($D)" | /bin/mail -s "(TEST MODE) $IP$PTR" $email
                                fi
                            fi

                        fi
            fi
        done < $TMP_LOG

    while read line
        do
            if [[ "$line" == *=* ]]; then
                GET_TIME=`echo $line | awk -F"=" '{print $2}'`
                NOW=`date +%s`
                #echo $NOW
                #echo $GET_TIME
                if [ "$NOW" -gt "$GET_TIME" ]; then
                    IP=` echo $line | awk '{print $1}'`
                    echo "`date`. $IP unblocked." >> $LOGFILE2
                    /bin/sed -i '/'$IP'/d' $LOGFILE
                    /sbin/iptables -D INPUT -s $IP -j DROP
                #else
                    #echo "Nothing to do"
               fi
            fi
        done < $LOGFILE
done
</code>

<code class="no">#!/bin/bash
# traffic_protect v.1.0.3

SIZE=1000 # in Gb
DOMAIN_LIST="lifehacker.ru"
BLOCK_TIME=10 #in minutes
IP_WHITELIST=""
RESOLVE_IP=true # resolve IP
BLOCK_ENABLE=false # true/false - enable/disable add firewall rule.
email="" # email="" - not send email
TMPMAIL=/tmp/

LOGFILE=/var/log/traffic-current.log
LOGFILE2=/var/log/traffic-history.log

IPT="/sbin/iptables"

for D in $DOMAIN_LIST
do

    TMP_LOG=/tmp/traffic-$D-acc-temp.log
    TMPMAIL=/tmp/traffic-$D-mail.tmp
    NGINX_LOG=/srv/www/$D/logs/$D-acc

    if [ ! -f $NGINX_LOG ]; then
        echo "Log ($NGINX_LOG) not found."
        exit 1
    fi

    if [ ! -f $LOGFILE ]; then
        touch $LOGFILE
    fi

    DT=`date +%d/%h/%Y`
    let "BYTE = $SIZE * 1048576"
    cat $NGINX_LOG | grep $DT | sort -n -k 1 | awk '{print $1,"\t",$10}' | awk '{sum[$1]+=$2}END{for(i in sum) print i,sum[i]}'| awk -v x=$BYTE ' $2 > x {print $1,$2} ' > $TMP_LOG

    # Разблокировка адресов
    while read line
        do
            if [[ "$line" == *=* ]]; then
                GET_TIME=`echo $line | awk -F"=" '{print $2}'`
                NOW=`date +%s`
                #echo $NOW
                #echo $GET_TIME
                if [ "$NOW" -gt "$GET_TIME" ]; then
                    IP=` echo $line | awk '{print $1}'`
                    echo "`date`. $IP unblocked." >> $LOGFILE2
                    /bin/sed -i '/'$IP'/d' $LOGFILE
                    $IPT -D INPUT -s $IP -j DROP
                #else
                    #echo "Nothing to do"
               fi
            fi
        done < $LOGFILE

    # Блокировка адресов
    while read line
        do
            IP=`echo $line | awk '{ print $1; }'`
            TRAFF=`echo $line | awk '{ print $2; }'`
	    let "TRAFFG = $TRAFF / 1048576"
            wh=0
            for I in $IP_WHITELIST
                do
                    if [ $I = $IP ]; then
                        wh=1
                    fi
                done
            if [ $wh = 1 ]; then
               echo "`date`. $IP in whitelist." >> $LOGFILE2
            else
                        DOUBLE=`/sbin/iptables-save | grep "\-j DROP" | grep "$IP"`
                        if [ -n "$DOUBLE" ]; then
                            echo "$IP exist in DROP rule. `date`" >> $LOGFILE2
                        else
                            PTR=""
                            if [ $RESOLVE_IP == true ]; then
				/usr/bin/host $IP >/dev/null
    		        	if [ $? -eq 0 ]
			        then
                            	    PTR=" (`/usr/bin/host $IP | awk '{ print $5 }'`)"
			        else
		                    PTR=""
			        fi
                            fi
                            if [ $BLOCK_ENABLE == true ]; then
                                echo "$IP blocked $BLOCK_TIME minutes `date` ($D) Unblock = `date --date="$BLOCK_TIME minute" +%s`" >> $LOGFILE
                                echo "`date`. $IP$PTR blocked $BLOCK_TIME minutes. Limit - $SIZE GB. Current - $TRAFFG GB. ($D)" >> $LOGFILE2
                                if [ $email  != "" ]; then
                                 #echo "`date` $IP$PTR blocked $BLOCK_TIME minutes. Limit - $SIZE GB. Current - $TRAFFG GB. ($D)" | /bin/mail -s "$IP$PTR Blocked" $email
				echo "Domain : $D" > $TMPMAIL
				echo "Mode   : Block mode." >> $TMPMAIL
				echo "Date   : `date`" >> $TMPMAIL
				echo "IP     : $IP" >> $TMPMAIL
				echo "Host   : $PTR" >> $TMPMAIL
				echo "Limit  : $SIZE GB" >> $TMPMAIL
				echo "Current: $TRAFFG GB" >> $TMPMAIL
                                /bin/mail -s "$IP$PTR blocked. Traff > $SIZE GB" $email < $TMPMAIL
                                fi
                                $IPT -A INPUT -s $IP -j DROP
                            else
                                echo "`date`. (TEST MODE) $IP$PTR. Limit - $SIZE GB. ($D)" >> $LOGFILE2
                                if [ $email  != "" ]; then
				echo "Domain : $D" > $TMPMAIL
				echo "Mode   : Test mode." >> $TMPMAIL
				echo "Date   : `date`" >> $TMPMAIL
				echo "IP     : $IP" >> $TMPMAIL
				echo "Host   : $PTR" >> $TMPMAIL
				echo "Limit  : $SIZE GB" >> $TMPMAIL
				echo "Current: $TRAFFG GB" >> $TMPMAIL
                                /bin/mail -s "(TEST MODE) $IP$PTR" $email < $TMPMAIL
                                fi
                            fi

                        fi
            fi
        done < $TMP_LOG

done
</code>

<code>sudo aptitude install php5-common php5-dev php5-mysql php5-sqlite php5-tidy php5-xmlrpc php5-xsl php5-cgi php5-mcrypt php5-curl php5-gd php5-mhash php5-pspell php5-snmp php5-sqlite libmagick9-dev php5-cli</code>
<code>sudo aptitude install mysql-server mysql-client libmysqlclient15-dev</code>
<code>sudo aptitude install libapache2-mod-rpaf</code>
	</p>
</div>
