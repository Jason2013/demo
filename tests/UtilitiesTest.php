<?php
use PHPUnit\Framework\TestCase;

include __DIR__ . "/../phplibs/configuration/swtMISConst.php";

final class UtilitiesTest extends TestCase
{
    public function testReplaceOldCardName()
    {
        $this->assertEquals("GTX1080", ReplaceOldCardName("GTX 1080"));
        $this->assertEquals("RTX2070", ReplaceOldCardName("GTX2070"), "RTX2070");
        $this->assertEquals("NAVI10", ReplaceOldCardName("NAVI10"));
    }

    public function testReplaceOldUmdName()
    {
        $this->assertEquals("D3D11", ReplaceOldUmdName("DX11"));
        $this->assertEquals("D3D12", ReplaceOldUmdName("DX12"));
        $this->assertEquals("Vulkan", ReplaceOldUmdName("Vulkan"));
    }

    public function testCanBeUsedAsString()
    {

    }
}
