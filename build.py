# coding=utf-8

import os

print(os.environ["APPVEYOR_BUILD_WORKER_IMAGE"])
print(os.environ["Configuration"])
print(os.environ["Platform"])

VS = os.environ["APPVEYOR_BUILD_WORKER_IMAGE"]
Config = os.environ["Configuration"]
Platform = os.environ["Platform"]

  # Visual Studio 15 2017 [arch] = Generates Visual Studio 2017 project files.
  # Visual Studio 14 2015 [arch] = Generates Visual Studio 2015 project files.
  # Visual Studio 12 2013 [arch] = Generates Visual Studio 2013 project files.
  # Visual Studio 11 2012 [arch] = Generates Visual Studio 2012 project files.
  # Visual Studio 10 2010 [arch] = Generates Visual Studio 2010 project files.
  # Visual Studio 9 2008 [arch]  = Generates Visual Studio 2008 project files.

# "Visual Studio 15 2017" : "Visual Studio 2017",
# "Visual Studio 14 2015" : "Visual Studio 2015",
# "Visual Studio 12 2013" : "Visual Studio 2013",
# "Visual Studio 11 2012" : "Visual Studio 2012",
# "Visual Studio 10 2010" : "Visual Studio 2010",
# "Visual Studio 9 2008" : "Visual Studio 2008",

VS_MAP = {
    "Visual Studio 2017" : "Visual Studio 15 2017",
    "Visual Studio 2015" : "Visual Studio 14 2015",
    "Visual Studio 2013" : "Visual Studio 12 2013",
    "Visual Studio 2012" : "Visual Studio 11 2012",
    "Visual Studio 2010" : "Visual Studio 10 2010",
    "Visual Studio 2008" : "Visual Studio 9 2008",
}

Generator = VS_MAP[VS]
if Platform == "x64":
    Generator += " Win64"

CMAKE_COMMAND1 = 'cmake -G"{GENERATOR} ..'.format(GENERATOR=Generator)
CMAKE_COMMAND2 = 'cmake --build . --config ' + Config

CMAKE_COMMANDS = ["mkdir build",
    "cd build",
    CMAKE_COMMAND1,
    CMAKE_COMMAND2,
    "cd ..",
    "\n",
]

with open("build.bat", "w") as f:
    f.write("\n".join(CMAKE_COMMANDS))
