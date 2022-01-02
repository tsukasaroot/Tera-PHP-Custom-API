package com.tera

import grails.testing.mixin.integration.Integration
import grails.gorm.transactions.Rollback
import spock.lang.Specification
import org.hibernate.SessionFactory

@Integration
@Rollback
class AccountServiceSpec extends Specification {

    AccountService accountService
    SessionFactory sessionFactory

    private Long setupData() {
        // TODO: Populate valid domain instances and return a valid ID
        //new Account(...).save(flush: true, failOnError: true)
        //new Account(...).save(flush: true, failOnError: true)
        //Account account = new Account(...).save(flush: true, failOnError: true)
        //new Account(...).save(flush: true, failOnError: true)
        //new Account(...).save(flush: true, failOnError: true)
        assert false, "TODO: Provide a setupData() implementation for this generated test suite"
        //account.id
    }

    void "test get"() {
        setupData()

        expect:
        accountService.get(1) != null
    }

    void "test list"() {
        setupData()

        when:
        List<Account> accountList = accountService.list(max: 2, offset: 2)

        then:
        accountList.size() == 2
        assert false, "TODO: Verify the correct instances are returned"
    }

    void "test count"() {
        setupData()

        expect:
        accountService.count() == 5
    }

    void "test delete"() {
        Long accountId = setupData()

        expect:
        accountService.count() == 5

        when:
        accountService.delete(accountId)
        sessionFactory.currentSession.flush()

        then:
        accountService.count() == 4
    }

    void "test save"() {
        when:
        assert false, "TODO: Provide a valid instance to save"
        Account account = new Account()
        accountService.save(account)

        then:
        account.id != null
    }
}
