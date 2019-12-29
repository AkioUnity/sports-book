#!/bin/sh

################################################################################
#
# Bake is a shell script for running ChalkPro installation
# PHP 5
#
#
################################################################################

cd ../

# Permissions
# find . -type d -exec chmod -R 755 {} \;
# find . -type f -exec chmod -R 644 {} \;
# find . -type f -name 'index.php' -exec chmod -R 600 {} \;

echo "--------------------------------------------------------------------";
echo "Required directories check start:"
echo "====================================================================";
echo "";

if [ ! -d app/tmp ]; then
    mkdir -p app/tmp
    chmod -R 0777 app/tmp
    echo "* app/tmp created"
else
    echo "* app/tmp already exists"
fi

if [ ! -d app/tmp/cache ]; then
    mkdir -p app/tmp/cache
    chmod -R 0777 app/tmp/cache
    echo "* app/tmp/cache created"
else
    echo "* app/tmp/cache already exists and later will be truncated"
fi

if [ ! -d app/tmp/cache/models ]; then
    mkdir -p app/tmp/cache/models
    chmod -R 0777 app/tmp/cache/models
    echo "* app/tmp/cache/models created"
else
    echo "* app/tmp/cache/models already exists and later will be truncated"
fi

if [ ! -d app/tmp/cache/persistent ]; then
    mkdir -p app/tmp/cache/persistent
    chmod -R 0777 app/tmp/cache/persistent
    echo "* app/tmp/cache/persistent created"
else
    echo "* app/tmp/cache/persistent already exists and later will be truncated"
fi

if [ ! -d app/tmp/logs ]; then
    mkdir -p app/tmp/logs
    chmod -R 0777 app/tmp/logs
    echo "* app/tmp/logs created"
else
    echo "* app/tmp/logs already exists keep as it is"
fi

if [ ! -d app/tmp/xml ]; then
    mkdir -p app/tmp/xml
    chmod -R 0777 app/tmp/xml
    echo "* app/tmp/xml created"
else
    echo "* app/tmp/xml already exists keep as it is"
fi

if [ ! -d app/webroot/media ]; then
    mkdir -p app/webroot/media
    chmod -R 0777 app/webroot/media
    echo "* app/webroot/media created"
else
    echo "* app/webroot/media already exists, permissions updatated"
    chmod -R 0777 app/webroot/media
fi

if [ ! -d app/webroot/theme ]; then
    mkdir -p app/webroot/theme
    chmod -R 0777 app/webroot/theme
    echo "* app/webroot/theme created"
else
    echo "* app/webroot/theme already exists and later will be truncated"
fi

if [ ! -d app/webroot/uploads ]; then
    mkdir -p app/webroot/uploads
    chmod -R 0777 app/webroot/uploads
    echo "* app/webroot/uploads created"
else
    echo "* app/webroot/uploads already exists keep as it is"
fi
echo "";
echo "- Required directories check end."

# Repo update
echo "";
echo "--------------------------------------------------------------------";
echo "Repository update start:"
echo "====================================================================";
echo "";
git pull
echo "";
echo "- Repository update end."

# Symlink flush
echo "";
echo "--------------------------------------------------------------------";
echo "Flush themed links:"
echo "====================================================================";
echo "";
# Admin
if [ -d app/webroot/theme/Admin ]; then
    echo "* Admin Theme Symlink Is A Directory";
else
    rm app/webroot/theme/Admin
    echo "* Flushed Admin Theme Symlink";
fi

# Project
if [ -d app/webroot/theme/ChalkPro ]; then
    echo "* Project Theme Symlink Is A Directory";
else
    rm app/webroot/theme/ChalkPro
    echo "* Flushed Project Theme Symlink";
fi

echo "";
echo "- Flush themed links end."

# Clear cache
echo "";
echo "--------------------------------------------------------------------";
echo "Flush cache start:"
echo "====================================================================";
echo "";
rm -rf app/tmp/cache/models/*
echo "* Flushed models cache";
rm -rf app/tmp/cache/persistent/*
echo "* Flushed persistent cache";
rm -rf app/tmp/tickets/pdf/*
echo "* Flushed tickets cache";
echo "";
echo "- Flush cache end."

# Exit message
echo "";
echo "Update done. Exiting.";
