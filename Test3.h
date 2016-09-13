#ifndef __TEST3_H
#define __TEST3_H

#include <iostream>
#include "BaseTest.h"

class Test3 : public BaseTest
{
public:
    Test3() { std::cout << "Test3()" << std::endl; }
    ~Test3() { std::cout << "~Test3()" << std::endl; }
};

#endif // __TEST3_H
