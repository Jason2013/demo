#ifndef __TEST1_H
#define __TEST1_H

#include <iostream>
#include "BaseTest.h"

class Test1 : public BaseTest
{
public:
    Test1() { std::cout << "Test1()" << std::endl; }
    ~Test1() { std::cout << "~Test1()" << std::endl; }
};

#endif // __TEST1_H
