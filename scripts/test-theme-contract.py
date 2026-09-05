#!/usr/bin/env python3
from pathlib import Path
import re

ROOT = Path(__file__).resolve().parents[1]
THEME = ROOT / "rodytech-theme"


def relative_luminance(hex_color: str) -> float:
    values = [int(hex_color[i : i + 2], 16) / 255 for i in (1, 3, 5)]
    linear = [value / 12.92 if value <= 0.04045 else ((value + 0.055) / 1.055) ** 2.4 for value in values]
    return 0.2126 * linear[0] + 0.7152 * linear[1] + 0.0722 * linear[2]


def contrast(foreground: str, background: str) -> float:
    bright, dark = sorted((relative_luminance(foreground), relative_luminance(background)), reverse=True)
    return (bright + 0.05) / (dark + 0.05)


header = (THEME / "header.php").read_text(encoding="utf-8")
functions = (THEME / "functions.php").read_text(encoding="utf-8")
author = (THEME / "author.php").read_text(encoding="utf-8")
styles = (THEME / "style.css").read_text(encoding="utf-8")
scripts = (THEME / "rodytech-animations.js").read_text(encoding="utf-8")
deploy = (ROOT / "scripts" / "deploy-theme.sh").read_text(encoding="utf-8")
smoke = (ROOT / "scripts" / "smoke-test.sh").read_text(encoding="utf-8")
deploy_tests = (ROOT / "scripts" / "test-deploy-gates.sh").read_text(encoding="utf-8")

assert "wp_body_open()" in header
assert 'class="nav-toggle"' in header and 'aria-controls="primary-navigation"' in header
assert 'class="nav-search"' in header and 'name="s"' in header
assert "initMobileNav();" in scripts and "event.key === 'Escape'" in scripts
assert "aria-expanded" in scripts and "nav-open" in scripts
assert ".nav-enhanced .main-nav.nav-open" in styles
assert "background: #09090b;" in styles
assert "width: 44px;" in styles and "height: 44px;" in styles
assert "min-height: 44px;" in styles
assert "<main" not in author.lower(), "author template must rely on the single main landmark opened by header.php"
assert "$target === '_blank'" in functions and "noopener" in functions and "noreferrer" in functions
assert "wp_nav_menu" in header and "'depth'          => 3" in header
assert "menu_item_parent !== 0" not in functions

assert "--accent: #8b8ff8;" in styles
assert "--accent-rgb: 139, 143, 248;" in styles
for legacy_orange in ("255,102,0", "255, 102, 0", "255,133,51", "255, 133, 51", "255,126,45", "255, 126, 45"):
    assert legacy_orange not in styles
assert "#ff6600" not in styles.lower()
muted = re.search(r"--text-muted:\s*(#[0-9a-fA-F]{6})", styles).group(1)
assert contrast(muted, "#09090B") >= 4.5

assert 'TARGET="staging"' in deploy
assert "--confirm-production" in deploy and "--expected-sha" in deploy
assert "Production deploy refused" in deploy
assert "status --porcelain" in deploy
assert "validate_remote_path" in deploy and "ALLOWED_THEME_PREFIX" in deploy
assert "expected_refusal" not in deploy_tests
assert "expect_refusal" in deploy_tests and "verify < backup < rsync < permissions < smoke" in deploy_tests
assert "hardcoded" not in smoke.lower()
assert "wp-json/wp/v2/posts" in smoke

combined = "\n".join(path.read_text(encoding="utf-8", errors="ignore") for path in THEME.rglob("*") if path.is_file())
for marker in ("adsense", "doubleclick", "googletag", "data-ad-slot"):
    assert marker not in combined.lower()

print("theme_contract_ok")
