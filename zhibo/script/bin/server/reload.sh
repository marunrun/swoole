#! /usr/bin/sh
pid=`pidof live_server`
echo $pid
kill -USR1 ${pid}
