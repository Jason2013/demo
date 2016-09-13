#pragma once
#include <map>
#include <string>
#include <iostream>

class TestBase;
class Tests
{
    public:
        static Tests* instance();
        void registerTest(const std::string& name, TestBase * (*factory)() = nullptr);
        std::map<std::string, TestBase * (*)()>& getNames();
        Tests() { std::cout << "Tests()" << std::endl; }
        ~Tests() { std::cout << "~Tests()" << std::endl; }
    private:
        std::map<std::string, TestBase * (*)()> _names;
};
