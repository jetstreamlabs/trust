# Snowflake Changelog

# 1.0.0 (2022-01-23)


### Breaking

* **init:** initial commit prior to any work ([c185e33](https://github.com/jetstreamlabs/trust/commit/c185e333586f2bb950d0bf1960a6d9dfa0349f23))


### Bug Fixes

* repair workflow and add style ci on commit ([5d2ad4e](https://github.com/jetstreamlabs/trust/commit/5d2ad4e3936b6c8e209f0b08470dd529c5203ddb))


### Features

* add semantic-release to package ([1c86eba](https://github.com/jetstreamlabs/trust/commit/1c86ebabdd0b2510e9317882c8ba75470419de71))


### Maintenance

* **init:** ammend gitignore ([44aa54d](https://github.com/jetstreamlabs/trust/commit/44aa54d90192923e0a601185c55e5264e2048daf))
* **init:** initial commit of gitignore ([1396435](https://github.com/jetstreamlabs/trust/commit/1396435397e59d50df22133eba4f5934e97b0258))
* search/replace error correction ([1262195](https://github.com/jetstreamlabs/trust/commit/1262195aa0511dacfd4dc2a442c0a1d996a60a85))
* **style:** apply fixes from StyleCI ([0da5fae](https://github.com/jetstreamlabs/trust/commit/0da5fae054b049a956dcd93b6f9832ac66271428))
* **style:** apply style fixes from style ci ([3f7d7b7](https://github.com/jetstreamlabs/trust/commit/3f7d7b758a23f440d2287bcba8c560be0c7ca5ab))

## 6.1.0 (mayo 29, 2020)

- Merge branch 'limit-roles-in-panel' into 6.x
- Update docs
- Update docs
- Use a default model when entering the roles assignment view
- Use display name when available in the panel
- Build for production
- Little improvements on the not removable roles and defaults for previously installed versions
- Add show view for the not editable roles
- Update docs
- Add config file structure
- Add possibility to avoid having roles removed from an user
- Add the possibility to block roles for edit and delete

## 6.0.2 (mayo 11, 2020)

- Merge pull request #411 from siarheipashkevich/fix-config-typos
- Fixed config typos
- Update docs
- Merge branch '6.x'
- Fix broken links and update sitemap
- Merge branch '6.x'
- Add some screenshots to the docs
- Merge branch '6.x'

## 6.0.1 (mayo 07, 2020)

- Don't register the panel by default

## 6.0.0 (mayo 06, 2020)

- Add simple admin panel to manage roles, permissions and roles/permissions assignment to the users
- Change how the Seeder works, in order to only use the role structure we had before
- Remove the method `can` so we now support gates and policies out of the box
- Add `withoutRole` and `withoutPermission` scopes
- Add support to receive multiple roles and permisions in the `whereRoleIs` and `wherePermissionIs` methods.
- Trust is now using semver.
