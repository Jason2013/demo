#ifndef __TEST2_H
#define __TEST2_H

#include <iostream>
#include "BaseTest.h"

class Test2 : public BaseTest
{
public:
    Test2() { std::cout << "Test2()" << std::endl; }
    ~Test2() { std::cout << "~Test2()" << std::endl; }
};

#endif // __TEST2_H
