#ifndef __BASE_TEST_H
#define __BASE_TEST_H
#include "BenchApp.h"

class BaseTest
{
    public:
        virtual ~BaseTest() {}; // base class always needs a virtual destructor
};

class TestReg
{
public:
    TestReg(const std::string& name, TEST_FACTORY_FUNC factory)
    {
        GetBenchApp()->registerTest(name, factory);
    }
};

#define REGISTER_TEST(testname) \
BaseTest * testname ## Factory() \
{ \
    return new testname(); \
} \
TestReg TestReg ## testname(# testname, testname ## Factory);

#endif // __BASE_TEST_H
