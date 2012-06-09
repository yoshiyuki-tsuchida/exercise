#! /bin/sh

########################################
# Name:
#
# about:
#
# Usage:
#
# Author:
# Date:
########################################

# Const
INIT_SQL='init.sql'

submoduleの追加
echo -e "\033[0;43mgit submodule addition.\033[0;39m"
cd ../
git submodule init;
git submodule update;

cd migration
echo -e "\\n\033[0;43mEnter mysql root password. \033[0;39m"
/Applications/MAMP/Library/bin/mysql -u root -p <$INIT_SQL;

echo -e "\\n\033[0;43mExicution of all sql files.\033[0;39m"
for sqlfile in *.sql
do

    if [ $sqlfile != ${INIT_SQL} ] 
    then
        echo "<$sqlfile"
        /Applications/MAMP/Library/bin/mysql -u ecsite_user -pecsite_pass ecsite_db <$sqlfile
    fi
done

echo -e "\\n\033[0;43mDao Test.\033[0;39m"
for testfile in ../tests/lib/php/Db/Dao/*Test.php
do
        echo "$testfile"
        phpunit --colors $testfile
done

echo -e "\\n\033[0;43mModel Test.\033[0;39m"
for testfile in ../tests/models/*Test.php
do
        echo "$testfile"
        phpunit --colors $testfile
done

echo -e "\\n\033[0;43mlib Test.\033[0;39m"
for testfile in ../tests/lib/php/*Test.php
do
        echo "$testfile"
        phpunit --colors $testfile
done


echo -e "\\n\033[0;43mFormValidator Test.\033[0;39m"
for testfile in ../tests/lib/php/FormValidator/*Test.php
do
        echo "$testfile"
        phpunit --colors $testfile
done
