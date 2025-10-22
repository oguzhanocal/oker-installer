#!/bin/bash
# Oğuzhan Öcal - Oker Laravel Installer (MySQL Varsayılan, Türkçe)
# Kullanım: bash <(curl -s https://raw.githubusercontent.com/oguzhanocal/oker-installer/main/install.sh)

echo "🌙 Oker Laravel Kurulum Başlatılıyor..."
WEB_ROOT="/var/www/vhosts/oguzhanocal.com.tr/oker.oguzhanocal.com/httpdocs"

cd $WEB_ROOT || { echo "❌ Dizin bulunamadı: $WEB_ROOT"; exit 1; }

echo "➡️  Dosyalar indiriliyor..."
wget -q https://raw.githubusercontent.com/oguzhanocal/oker-installer/main/install.php -O install.php
wget -q https://raw.githubusercontent.com/oguzhanocal/oker-installer/main/overlay.zip -O overlay.zip
wget -q https://raw.githubusercontent.com/oguzhanocal/oker-installer/main/README.md -O README.md

chown -R pleskphpuser:psacln $WEB_ROOT
chmod -R 755 $WEB_ROOT

echo "✅ Kurulum sihirbazı hazır!"
echo "Tarayıcıdan şuraya gidin:"
echo "👉 https://oker.oguzhanocal.com/install.php"
