# coding=utf-8

import unittest
from typing import List

# Definition for singly-linked list.
class ListNode:
    def __init__(self, x):
        self.val = x
        self.next = None

# CODE START
class Solution:
    def addTwoNumbers(self, l1: ListNode, l2: ListNode) -> ListNode:
        head = ListNode(-1)
        prev = head
        carry = 0
        while l1 is not None and l2 is not None:

            digit = l1.val + l2.val + carry
            if digit > 9:
                carry = 1
                digit -= 10
            else:
                carry = 0

            curr = ListNode(digit)
            prev.next = curr
            prev = curr

            l1 = l1.next
            l2 = l2.next

        remain = l1 if l2 is None else l2
        while remain is not None:
            digit = remain.val + carry
            if digit > 9:
                carry = 1
                digit -= 10
            else:
                carry = 0
            curr = ListNode(digit)
            prev.next = curr
            prev = curr

            remain = remain.next

        if carry > 0:
            curr = ListNode(carry)
            prev.next = curr

        return head.next
# CODE END

        
class MyTest(unittest.TestCase):

    def makeList(self, nums : List[int]) -> ListNode:
        head = ListNode(-1)
        prev = head
        for n in nums:
            curr = ListNode(n)
            prev.next = curr
            prev = curr

        return head.next

    def cmpList(self, nums : List[int], head: ListNode) -> bool:
        for n in nums:
            if head is None or n != head.val:
                return False
            head = head.next
        return head is None

    def test_0(self):
        nums = [1, 3, 5]
        head = self.makeList(nums)
        self.assertTrue(self.cmpList(nums, head))


    def setUp(self):
        self.obj = Solution()

    def tearDown(self):
        del self.obj

    def test_1(self):
        nums1 = [2, 4, 3]
        nums2 = [5, 6, 4]
        nums3 = [7, 0, 8]

        l1 = self.makeList(nums1)
        l2 = self.makeList(nums2)
        l3 = self.obj.addTwoNumbers(l1, l2)
        self.assertTrue(self.cmpList(nums3, l3))

    def test_2(self):
        nums1 = [1]
        nums2 = [9, 9, 9]
        nums3 = [0, 0, 0, 1]

        l1 = self.makeList(nums1)
        l2 = self.makeList(nums2)
        l3 = self.obj.addTwoNumbers(l1, l2)
        self.assertTrue(self.cmpList(nums3, l3))


if __name__ == "__main__":
    unittest.main()
