#include "BenchApp.h"
#include <memory>

void BenchApp::registerTest(const std::string& name, TEST_FACTORY_FUNC factory)
{
    _testFactories.insert(make_pair(name, factory));
}

TEST_FACTORY_MAP& BenchApp::getTestFactories()
{
    return _testFactories;
}

BenchApp* GetBenchApp()
{
    static std::unique_ptr<BenchApp> gBenchApp;
    if (!gBenchApp)
    {
        gBenchApp.reset(new BenchApp());
    }
    return gBenchApp.get();
}
