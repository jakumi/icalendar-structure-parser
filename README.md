The icalendar format parser can parse THE STRUCTURE of data streams of the iCalendar variety for example:

* https://tools.ietf.org/html/rfc2425 ,
* https://tools.ietf.org/html/rfc2445 ,
* https://tools.ietf.org/html/rfc5545 ,
* https://tools.ietf.org/html/rfc7986

When saying "the structure", it means, it doesn't generally interpret/use the meaning/semantics of parameters, properties, any values.
The only thing that is used is the count each parameter can appear in each component.
This information is used solely for the magic `__get` function on (known) components.

The parser does not:

* check for correctness.
* enforce any restrictions, limitations or anything
* decode values of any kind (helper functions might be added, to aid with that)
