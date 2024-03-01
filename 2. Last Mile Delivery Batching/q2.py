import datetime
from collections import defaultdict

class Order:
    def __init__(self, orderid, kitchenid, custid, readytime):
        self.orderid = orderid
        self.kitchenid = kitchenid
        self.custid = custid
        self.readytime = readytime

class DeliveryScheduler:
    def __init__(self):
        self.orders = defaultdict(list)

    def addorder(self, order):
        self.orders[(order.kitchenid, order.custid, order.readytime)].append(order)

    def assignriders(self):
        riderassign = defaultdict(list)
        currtime = datetime.datetime.now()

        for key, orderlist in self.orders.items():
            orderlist.sort(key=lambda x: x.readytime)

            for i, order in enumerate(orderlist):
                assigned = False

                for rider, orders in riderassign.items():
                    if (
                        order.kitchenid == orders[0].kitchenid and
                        (order.readytime - currtime).total_seconds() <= 600
                    ):
                        if i > 0 and orderlist[i - 1].custid == rider[0]:
                            orders.append(order)
                            assigned = True
                            break
                        elif i > 0 and orderlist[i - 1].custid == rider[1]:
                            orders.append(order)
                            assigned = True
                            break

                if not assigned:
                    new_rider = (order.custid,)
                    riderassign[new_rider].append(order)

        for i, (rider, orders) in enumerate(riderassign.items()):
            print(f"Assign Rider {i + 1} to orders {', '.join(map(lambda x: str(x.orderid), orders))}")

scheduler = DeliveryScheduler()

order1 = Order(1, "KitchenA", "CustomerX", datetime.datetime.now() + datetime.timedelta(minutes=5))
order2 = Order(2, "KitchenA", "CustomerY", datetime.datetime.now() + datetime.timedelta(minutes=5))
order3 = Order(3, "KitchenB", "CustomerX", datetime.datetime.now() + datetime.timedelta(minutes=10))
order4 = Order(4, "KitchenB", "CustomerY", datetime.datetime.now() + datetime.timedelta(minutes=10))

scheduler.addorder(order1)
scheduler.addorder(order2)
scheduler.addorder(order3)
scheduler.addorder(order4)

scheduler.assignriders()