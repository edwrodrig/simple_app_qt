QT += widgets

CONFIG += c++11
QMAKE_CXXFLAGS += -Wall -Wextra -Werror

TARGET = simple_test

INCLUDEPATH += ../lib
LIBS += -L../lib -lsimple_test_lib

SOURCES = main.cpp

