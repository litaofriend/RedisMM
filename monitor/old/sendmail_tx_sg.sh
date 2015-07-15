#!/bin/bash
PATH=/usr/local/bin:/usr/bin:/usr/local/mysql/bin:/bin:/usr/sbin:.
export PATH

if [ $# -lt 4 ];then
   echo "$0 mail_user to_list sub content_file"
   exit
fi

mail_user=$1
to_list=$2
sub=$3
content_file=$4
echo $to_list|sed "s/ //g" |awk -F';' '{i=1;while(i<=NF){if($i!="") print $i;i++}}'|sort|uniq > tx_name.txt
join -1 1 -2 1 tx_name.txt tx_sogou_name.txt |awk '{if($2!="") print $2"@sogou-inc.com;"}'  > mail_name.txt
join -1 1 -2 1 -v1 tx_name.txt tx_sogou_name.txt |awk '{if($0!="") print $0"@tencent.com;"}' >> mail_name.txt
receive=`cat mail_name.txt |tr -d '\n'`

/usr/local/bin/sendmail2 "wenwenmail" "$receive" "" "$sub"  "$content_file"

