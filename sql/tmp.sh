echo "create database if not exists db_RedisMM;"> redis_monitor.sql
echo "use db_RedisMM;">> redis_monitor.sql

/usr/local/mysql/bin/mysql -uroot -proot95 -S /data/mysql_data_3306/mysql.sock redis_monitor -N -e "show tables"|while read table
do
  echo "DROP TABLE IF EXISTS $table;" >> redis_monitor.sql
  /usr/local/mysql/bin/mysql -uroot -proot95 -S /data/mysql_data_3306/mysql.sock redis_monitor -N -e "set names latin1;show create table $table\G" |awk -v table="$table" '{if($1!=table) print $0}' >> redis_monitor.sql
  echo ";" >> redis_monitor.sql
  #sed "/$table/d" redis_monitor.sql
done

/usr/local/mysql/bin/mysqldump -uroot -proot95 -S /data/mysql_data_3306/mysql.sock redis_monitor options   --default-character-set=latin1 >> redis_monitor.sql

grep -v "1. row" redis_monitor.sql|sed -e "s/p_sgtli/admin/" > redis_monitor.sql.tmp
mv redis_monitor.sql.tmp redis_monitor.sql

echo "INSERT INTO admin_user VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', 'all', 'administrator', '', '', '1', '127.0.0.1', '2014-02-15 20:47:47', '1', '2013-12-25 15:58:34');">> redis_monitor.sql
