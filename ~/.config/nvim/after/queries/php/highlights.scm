;; Extends
(name) @variable
(variable_name) @variable
(name) @function.method
(name) @function.call

((name) @constant
 (#match? @constant "^[A-Z][A-Z_]*$"))

((name) @constructor
 (#match? @constructor "^[A-Z]"))

((name) @variable.builtin
 (#match? @variable.builtin "^(self|static|parent)$"))
