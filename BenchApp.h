#ifndef __BENCH_APP_H
#define __BENCH_APP_H

#include <map>
#include <string>
#include <iostream>

class BaseTest;

using TEST_FACTORY_FUNC = BaseTest* (*)();
using TEST_FACTORY_MAP = std::map<std::string, TEST_FACTORY_FUNC>;

class BenchApp
{
public:
    BenchApp() { std::cout << "BenchApp()" << std::endl; }
    ~BenchApp() { std::cout << "~BenchApp()" << std::endl; }
    void registerTest(const std::string& name, TEST_FACTORY_FUNC factory = nullptr);
    TEST_FACTORY_MAP& getTestFactories();
private:
    TEST_FACTORY_MAP _testFactories;
};

BenchApp* GetBenchApp();

#endif // __BENCH_APP_H
