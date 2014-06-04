#Important considerations:

## Requested resources

We expect to have a valid requested resources string.That means that each job should have h_vmem and h_rt. If not the entry is not considered valid.
If you don't have such requirerments, you must modify the parser and the table :

- Parser: remove all eff_* and requested_* variables
- MySQL table, remove NOT NULL from :
```
requested_time int(10) NOT NULL,
requested_mem float(15,2) NOT NULL,
time_eff float(15,2) NOT NULL,
time_resource_eff float(15,2) NOT NULL,
mem_eff float(15,2) NOT NULL,
```

## Apache
You need a valid DNS entry like the one we have in http.conf file:

 ServerName accounting.linux.crg.es
