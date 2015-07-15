cat user.txt|while read name1 name
do
  pass=`echo -n $name|md5sum|awk '{print $1}'`
  sql="replace into mysqlmtop.admin_user(username,password,realname) values('$name','$pass','$name1')"
  echo $sql|mysql -uroot -proot26 --default-character-set=utf8
  echo $sql
done
