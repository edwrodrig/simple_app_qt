QT += widgets

CONFIG += c++11
QMAKE_CXXFLAGS += -Wall -Wextra -Werror

TARGET = simple_test_lib
TEMPLATE = lib
CONFIG += staticlib

SOURCES = \
    mainwindow.cpp

HEADERS += \
    mainwindow.h

