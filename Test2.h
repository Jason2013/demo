#pragma once
#include "TestBase.h"

class Test2 : public TestBase
{
public:
    Test2() { std::cout << "Test2()" << std::endl; }
    ~Test2() { std::cout << "~Test2()" << std::endl; }
};
