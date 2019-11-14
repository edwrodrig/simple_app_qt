emake clean
emake
rm -rf deploy
mkdir deploy
cp build/tundra-piechart*.exe "deploy/"
cd deploy

QTPATH="/c/Qt/5.4/mingw491_32"
TARGETDIR="."
SOURCEDIR="."

function r {
cp -f "$QTPATH"/"$SOURCEDIR"/"$1" "$TARGETDIR"
}

function rdir {
rm -rf "$TARGETDIR"
mkdir "$TARGETDIR"
}

SOURCEDIR="bin"
TARGETDIR="."

r "icudt53.dll"
r "icuin53.dll"
r "icuuc53.dll"
r "libquazip.dll"
r "libz.dll"
r "libgcc_s_dw2-1.dll"
r "libstdc++-6.dll"
r "libwinpthread-1.dll"
r "Qt5Core.dll"
r "Qt5Gui.dll"
r "Qt5Network.dll"
r "Qt5Qml.dll"
r "Qt5Quick.dll"
r "Qt5Sql.dll"
r "Qt5Svg.dll"
r "Qt5Widgets.dll"

SOURCEDIR="plugins/imageformats"
TARGETDIR="imageformats"
rdir

r "qjpeg.dll"
r "qsvg.dll"

SOURCEDIR="plugins/platforms"
TARGETDIR="platforms"
rdir
r "qwindows.dll"

SOURCEDIR="plugins/bearer"
TARGETDIR="bearer"
rdir
r "qgenericbearer.dll"
r "qnativewifibearer.dll"


SOURCEDIR="plugins/iconengines"
TARGETDIR="iconengines"
rdir
r "qsvgicon.dll"

SOURCEDIR="plugins/sqldrivers"
TARGETDIR="sqldrivers"
rdir
r "qsqlite.dll"

