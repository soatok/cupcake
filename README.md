# Cupcake

[![Build Status](https://github.com/soatok/cupcake/actions/workflows/ci.yml/badge.svg)](https://github.com/soatok/cupcake/actions)
[![Latest Stable Version](https://poser.pugx.org/soatok/cupcake/v/stable)](https://packagist.org/packages/soatok/cupcake)
[![Latest Unstable Version](https://poser.pugx.org/soatok/cupcake/v/unstable)](https://packagist.org/packages/soatok/cupcake)
[![License](https://poser.pugx.org/soatok/cupcake/license)](https://packagist.org/packages/soatok/cupcake)

Sweet and fast form processing for PHP projects.

**Requires PHP 8 or newer.**

![Cupcake](docs/Neophyte/Cupcake.png)

## Installation

Use Composer to install this library:

```terminal
composer require soatok/cupcake
```

## Questions and Answers

### Why "Cupcake"?

Wordplay! The German word for a cupcake mold is förmchen.

### What Does Cupcake Do That Other Form Libraries Don't?

Three things: Security, simplicity, and ease-of-use. 

First, I designed Cupcake with security as its first principle.

This doesn't *just* mean the bare basics like preventing cross-site scripting
vulnerabilities and cross-site request forgery. Secure form processing means ensuring that [input validation isn't only performed client-side](https://soatok.blog/2020/04/27/why-server-side-input-validation-matters/).
To that end, Cupcake uses [Ionizer](https://github.com/paragonie/ionizer) for input filtering.

Second, Cupcake's interface is deliberately simple and intuitive. Piece o' cake!

Finally, Cupcake is easy to integrate with other platforms and frameworks,
by design. Cupcake has minimal dependencies and is unlikely to conflict with
your existing framework dependencies or explode the code size to an unreasonable
level.

(In the future, I will also provide shims for popular frameworks and ORMs.)

### How Do I Use Cupcake?

See the [Documentation](docs) directory.
