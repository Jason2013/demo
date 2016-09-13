#include <iostream>
#include <memory>
#include "Tests.h"
#include "TestBase.h"

int main()
{
    auto& factories = Tests::instance()->getNames();

    std::cout << factories.size() << std::endl;
    for (auto & name : factories)
    {
        std::cout << name.first << std::endl;
    }

    std::unique_ptr<TestBase> p1(factories["Test1"]());
    std::unique_ptr<TestBase> p2(factories["Test2"]());

    return 0;
}
