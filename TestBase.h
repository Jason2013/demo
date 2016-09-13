#pragma once
#include "Tests.h"

class TestReg
{
public:
    TestReg(const std::string& name, TestBase * (*factory)())
    {
        Tests::instance()->registerTest(name, factory);
    }
};

#define REGISTER_TEST(testname) \
    TestBase * testname ## Factory() \
    { \
        return new testname(); \
    } \
    TestReg TestReg ## testname(# testname, testname ## Factory);

class TestBase
{
    public:
        virtual ~TestBase() {}; // important !!!
};
