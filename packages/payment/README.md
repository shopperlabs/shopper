# Shopper Payment

The payment layer of [Shopper](https://laravelshopper.dev): the payment driver contract and
manager, the manual driver, the payment state machine of an order, refunds, the inbound webhook
ledger and the reconciliation command. Providers such as [`shopper/stripe`](https://github.com/shopperlabs/stripe)
register their driver on it.

This package is installed with [`shopper/framework`](https://github.com/shopperlabs/shopper).
It is not meant to be required on its own.

Read the [documentation](https://docs.laravelshopper.dev) to learn how Shopper works.
