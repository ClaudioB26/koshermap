{ pkgs, ... }: {
  channel = "stable-23.11";
  packages = [
    pkgs.php82
    pkgs.php82Packages.composer
    pkgs.nodejs_20
  ];
  idx.extensions = [
    "onecentlin.laravel-blade"
    "bmewburn.vscode-intelephense-client"
  ];
  idx.previews = {
    enable = true;
    previews = {
      web = {
        command = ["php" "artisan" "serve" "--port" "$PORT" "--host" "0.0.0.0"];
        manager = "web";
      };
    };
  };
}