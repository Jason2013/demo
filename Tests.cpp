#include "Tests.h"
#include <memory>

Tests* Tests::instance()
{
    static std::unique_ptr<Tests> gTests;
    if (!gTests)
    {
        gTests.reset(new Tests());
    }
    return gTests.get();
}
void Tests::registerTest(const std::string& name, TestBase * (*factory)())
{
    _names.insert(make_pair(name, factory));
}

std::map<std::string, TestBase * (*)()>& Tests::getNames()
{
    return _names;
}
