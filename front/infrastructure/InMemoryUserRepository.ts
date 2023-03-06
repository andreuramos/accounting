import User from '@domain/User'
import UserRepository from '@domain/UserRepository'

export default class InMemoryUserRepository implements UserRepository {
    private users: Array<User> = []
    
    list(): Array<User> {
        return this.users
    }
    add(user: User): void {
        this.users.push(user)
    }
}