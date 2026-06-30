#!/usr/bin/env bash
set -euo pipefail

cd "$(dirname "$0")/.."

if [ ! -d .git/hooks ]; then
    echo "This script must be run from a Git checkout." >&2
    exit 1
fi

cat > .git/hooks/post-merge <<'HOOK'
#!/usr/bin/env bash
set -euo pipefail

cd "$(git rev-parse --show-toplevel)"

echo "==> git pull completed; running cPanel deploy"
bash scripts/cpanel-deploy.sh
HOOK

chmod +x .git/hooks/post-merge

echo "Installed .git/hooks/post-merge. Future git pull commands will run scripts/cpanel-deploy.sh automatically."
