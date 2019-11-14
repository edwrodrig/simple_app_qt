#!/bin/sh

BASEDIR=$(dirname "$0")
VERSION=$(cat $BASEDIR/../data/version | tr -d \")
version=$VERSION-1

PACKAGE_APPDIR="bosque_$version/usr/local/bosque"

package_version=`echo $version | awk -F- '{print $2}'`

if [ -z $package_version ]; then
	echo "Directory $1 does not contains a '-' character"
	exit;
fi

mkdir -p "bosque_$version/usr/local/bin"
mkdir -p "bosque_$version/usr/share/applications"
mkdir -p "bosque_$version/DEBIAN"

cp -R bosque "bosque_$version/usr/local"

mkdir -p "bosque_$version/usr/local/bosque/icons"

cp $BASEDIR/../logos/logo32x32.png bosque_$version/usr/local/bosque/icons/bosque32x32.png

cat > bosque_$version/usr/share/applications/bosque.desktop << EOL
[Desktop Entry]
Name=Bosque
Comment=Advanced graphical Phylogenetic Software
GenericName=Bosque
Terminal=false
Icon=/usr/local/bosque/icons/bosque32x32.png
Type=Application
Exec=bosque
Categories=Education;Science;
EOL


rm -f $PACKAGE_APPDIR/bosque
cat > $PACKAGE_APPDIR/bosque << EOL
#/bin/sh
LD_LIBRARY_PATH=/usr/local/bosque
export LD_LIBRARY_PATH
exec /usr/local/bosque/app.binary \$@
EOL
chmod +x $PACKAGE_APPDIR/bosque

cd "bosque_$version/usr/local/bin"
ln -s ../bosque/bosque .
cd -

DEBIAN_DIR="bosque_$version/DEBIAN"
if [ ! -d "$DEBIAN_DIR" ]; then
	echo "$DEBIAN_DIR does not exists.. attempting to create it..."
	mkdir $DEBIAN_DIR
	if [ ! -d "$DEBIAN_DIR" ]; then
		echo "Unable to create $DEBIAN_DIR"
		exit
	fi
fi

cat > $DEBIAN_DIR/control << EOL
Package: bosque
Version: $version
Section: base
Priority: optional
Architecture: amd64
Maintainer: Edwin Rodriguez <edwin.rodriguez@imo-chile.cl>
Description: Bosque 
 Advanced graphical, user-friendly phylogenetic software
 Developed by:
	Edwin Rodriguez Leon <edwin.rodriguez@imo-chile.cl>
	Salvador Ramirez Flandes <sram@udec.cl>
 http://inf.imo-chile.cl/software/bosque.html
EOL
dpkg-deb --build bosque_$version
VERSION=$(echo $VERSION | tr . _)
mv bosque_$version\.deb bosque_$VERSION\_linux.deb
