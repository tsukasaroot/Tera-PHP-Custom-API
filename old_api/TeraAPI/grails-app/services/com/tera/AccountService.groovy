package com.tera

import grails.gorm.services.Service

@Service(Account)
interface AccountService {

    Account get(Serializable id)

    List<Account> list(Map args)

    Long count()

    void delete(Serializable id)

    Account save(Account account)

    
}