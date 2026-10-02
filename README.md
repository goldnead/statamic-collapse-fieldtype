# statamic-collapse-fieldtype
 A Collapse-Fieldtype for Statamic to beautifully hide and show your fields.

> [!IMPORTANT]
> **This addon is no longer maintained.** Statamic now ships this feature in core: the **Group** fieldtype has `collapsible` and `collapsed` options, and blueprint sections can be collapsible too. Please use those instead. There will be no Statamic 6 release of this addon.
>
> Thanks to everyone who used it and to the contributors who kept it alive for Statamic 4 and 5.

## Migrating to the core Group fieldtype

Group stores its values the same way this addon did: nested under the field handle. In most cases you only change the field type in your blueprints and fieldsets:

```diff
 handle: collapse
 field:
   display: 'Collapse This!'
-  type: collapse
+  type: group
+  collapsible: true
+  collapsed: true
   fields:
     ...
```

Your saved content and templates (`{{ collapse:section_width }}`) keep working unchanged. Afterwards remove the addon:

```
composer remove goldnead/statamic-collapse-fieldtype
```

Tested against Statamic 6.35. If you nest Grid or Replicator fields inside, check those entries once in the Control Panel.

---

Simple as that. Just tuck all the fields that take up too much space or you don't want to confuse your clients with et voilá: A beautiful little collapse-content section wherever you want that hides bloated fields.

## Installation

```
composer require goldnead/statamic-collapse-fieldtype
```

That's it. No vendors-assets needing attention or config files waiting to be configured. Just a plain ol' Fieldtype, nuthin' more.

## Usage

Behind the scenes this fieldtype kind of works like a `grid` or `replicator` fieldtype. Just without the repetition. Even conditions work!
Just use it in your fieldsets or blueprints like this:

```yaml
title: 'Content Theme'
fields:
  -
    handle: collapse
    field:
      fields:
        -
          handle: toggle_field
          field:
            default: false
            display: 'Test conditional fields!'
            type: toggle
            icon: toggle
            width: 50
            listable: hidden
        -
          handle: advanced_settings
          field:
            display: 'Advanced Settings'
            type: section
            icon: section
            listable: hidden
            if:
              toggle_field: 'equals true'
        -
          handle: section_width
          field:
            options:
              sm: SM
              md: MD
              lg: LG
              custom: Custom
            default: md
            display: 'Section Width'
            type: button_group
            icon: button_group
            width: 75
            listable: hidden
        -
          handle: custom_width
          field:
            default: '450'
            display: 'Custom Width'
            type: integer
            icon: integer
            width: 25
            listable: hidden
            if:
              section_width: 'equals custom'
      display: 'Collapse This!'
      type: collapse
      icon: asection
      listable: hidden
      instructions: "When you click here, something great will happen."
```

Easy-peasy, right?
You can also just use it in the blueprint editor.

## Templating

Like in the `grid` fieldset all values are scoped within that field. So to acces the variable `section_width` from the example above we need to write something like this in our templates:

```
<section width="{{ collapse:section_width }}>...</section>
```


### TODOs
- Enthusiastically added dark mode support to be used with [radnight](https://github.com/andymnewhouse/radnight). Need to figure out how to use dark-mode only when that kind of addon is being used. Otherwise it will be displayed in dark when everything else stays liht, yuck!
