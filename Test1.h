#pragma once
#include <iostream>
#include "TestBase.h"

class Test1 : public TestBase
{
public:
    Test1() { std::cout << "Test1()" << std::endl; }
    ~Test1() { std::cout << "~Test1()" << std::endl; }
};
