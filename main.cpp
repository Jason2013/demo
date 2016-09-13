#include <iostream>
#include <memory>
#include "BenchApp.h"
#include "BaseTest.h"

int main()
{
    auto& factories = GetBenchApp()->getTestFactories();

    std::cout << "test count = " << factories.size() << std::endl;
    for (auto & item : factories)
    {
        std::cout << "test name = " << item.first << std::endl;
        std::unique_ptr<BaseTest> pt(item.second());
    }

    return 0;
}
