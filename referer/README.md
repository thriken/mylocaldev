# referer 模块

Referer 防盗链绕过工具集，通过伪造 HTTP Referer 请求头来实现跨域资源访问和下载。

---

## 文件说明

### 1.php — 客户端伪造 Referer

向指定目标发送自定义 `Referer` 头的 HTTP 请求，用于验证防盗链绕过效果。

```
请求目标: music.local/referer/2.php
伪造来源: //www.jb51.net
```

**运行流程：**
1. 通过 `fsockopen()` 连接目标主机 80 端口
2. 构造原始 HTTP 请求，手动填充 `Referer` 头
3. 输出服务器返回的原始响应内容

### 2.php — 服务端验证脚本

被请求方，仅输出 `$_SERVER['HTTP_REFERER']` 的值，用于确认伪造的 Referer 是否成功到达。

与 `1.php` 配合使用：`1.php` 发出请求 → `2.php` 接收并回显 Referer。

### file.php — 代{过}{滤}理下载器（核心）

通过添加伪造的 Referer 头，代{过}{滤}理下载目标服务器上的文件，绕过防盗链限制。

**使用方式：**
```
/referer/file.php/http://example.com/path/to/file.mp4
```

**工作原理：**

1. **解析目标 URL** — 从请求路径中提取目标资源的完整 URL，分解出域名和资源路径
2. **伪造 Referer** — 将 Referer 设为与目标同域名，伪装成站内请求
3. **处理响应**：
   - **200 OK** → 直接强制下载文件
   - **302 跳转** → 防盗链系统先验证 Referer 再转向真实地址，脚本提取 `Location` 头中的真实地址并跟随跳转
4. **强制下载** — 设置 `Content-Type: application/force-download` 头触发浏览器下载行为

**特点：**
- 使用原始 socket 连接，灵活控制 HTTP 头
- 自动处理 302 重定向（防盗链系统常用手段）
- 同时设置 `User-Agent` 伪装浏览器身份

---

## 依赖

- **PHP 7.4+**
- 服务器需允许外发 socket 连接（使用原生 `fsockopen`）

### PHP 7.4+ 升级要点

| 文件 | 修复内容 |
|------|----------|
| `file.php` | `<?` → `<?php`；初始化 `$tp`；修复未定义变量 `$filename`；添加 `get_headers()` 返回校验；安全访问可能不存在的数组键 |
| `1.php` | 修复 HTTP 头行分隔符（`\n` → `\r\n`），符合 HTTP 规范 |
| `2.php` | 使用 `??` 空合并运算符，`HTTP_REFERER` 不存在时显示 `(无 Referer)` |

## 注意事项

此工具仅供学习 HTTP 协议和防盗链机制原理使用，请勿用于未经授权的资源下载。
