#!/bin/sh
#
# $1 - taskNo
# $2 - php
# $3 - user
# $4 - portscgi
# $7 - tmp

dir="${5}${3}${1}"
mkdir "${dir}"
echo $$ > "${dir}/pid"
chmod a+r "${dir}/pid"
cd "$(dirname $0)"
"${2}" ./createtorrent.php ${1} "${3}" ${4} >> "${dir}/errors" 2>> "${dir}/log"
last=$?
if [ $last -eq 0 ] ; then
	echo 'Done.' >> "${dir}/log"
else
	echo 'Error.' >> "${dir}/log"
fi
echo $last > "${dir}/status"
chmod a+r "${dir}/*"