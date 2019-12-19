# coding=utf-8

import unittest
from typing import List

# CODE START
class Solution:
    def twoSum(self, nums: List[int], target: int) -> List[int]:
        checked = {}
        for i, val in enumerate(nums):
            n = target -val
            if n in checked:
                return [checked[n], i]
            #else:
            checked[val] = i
# CODE END

        
class MyTest(unittest.TestCase):

    def setUp(self):
        self.obj = Solution()

    def tearDown(self):
        del self.obj

    def test_1(self):
        nums = [2, 7, 11, 15]
        target = 9
        result = self.obj.twoSum(nums, target)
        self.assertEqual(result, [0, 1])

    def test_2(self):
        nums = [2, 7, 11, 15]
        target = 17
        result = self.obj.twoSum(nums, target)
        self.assertEqual(result, [0, 3])

    def test_3(self):
        nums = [2, 7, 7, 15]
        target = 14
        result = self.obj.twoSum(nums, target)
        self.assertEqual(result, [1, 2])


if __name__ == "__main__":
    unittest.main()
