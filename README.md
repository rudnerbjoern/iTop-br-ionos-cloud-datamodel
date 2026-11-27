# IONOS Cloud data model for iTop

This extension adds IONOS-specific inventory to the provider-neutral Cloud data
model for iTop 3.3. The IONOS collector uses both modules to import virtual
datacenters, LANs, NAT and VPN gateways, and network load balancers.

See the [shared cloud inventory architecture](https://github.com/rudnerbjoern/iTop-br-cloud-datamodel/blob/main/docs/architecture/README.md) for the boundaries between the base model, this extension and the collector.

## Installation

1. Install the Cloud data model from the separate `iTop-br-cloud-datamodel`
   project. The required runtime module is `br-cloud-datamodel` version
   `0.3.0` or later. That project also provides the optional TeemIP bridge.
2. Extract the IONOS release ZIP and copy its outer `br-ionos-cloud-datamodel/`
   directory, containing `extension.xml`, into `<itop>/extensions/`.
3. Run iTop Setup and select the IONOS extension.

## IONOS datacenter attributes

`Cloud.ionos_sec_auth_protection` records whether the IONOS VDC requires
additional authentication. It defaults to **Unknown**.

## Network inventory ownership

The collector records its source in `ionos_source_key` when creating gateways,
LAN attachments, network load balancers and their address assignments. The
address classes also carry `ionos_synced_ip_id`, the iTop key of the last IPAM address
linked by the collector. A null or zero value indicates that it has no such
address on record. The collector uses these fields to distinguish its own
changes from manual assignments. They are omitted from the standard forms.
Both fields are available without TeemIP so the add-on can be installed on
either model variant.

VPN gateways record the IONOS region, tier and reported provisioning status.
The regional protocol list and configured organization, provider and contract
identify their source namespace. The gateway's public endpoint also carries
`ionos_synced_ip_id` so the collector can preserve manual changes to the
TeemIP reference.

## Optional provider and location samples

When installing a fresh iTop instance with the IONOS module, enable sample data to create the provider
organization `IONOS` (code `IONOS`) and its 11 public Compute API locations.
Each location uses its exact API identifier as its name, including `de/fra`,
`de/fra/1` and `de/fra/2`. City and country describe the location. Multiple
identifiers can refer to the same city. The snapshot was checked on
2026-11-26 against the [IONOS Cloud API location catalog](https://docs.ionos.com/cloud/api-reference/cloud-api-v6/locations/get-locations).

The XML uses local source identifiers. iTop assigns the database keys and
resolves each location's organization reference during setup. Existing CMDBs
with provider records should keep sample loading disabled and use their own
records. iTop's initial sample import can create duplicates if matching records
already exist. Ordinary upgrades do not refresh this snapshot.

## Development

See [local checks](.dev/tests/README.md) and [release preparation](scripts/README.md).
Run the Markdown and spelling checks from the repository root:

```sh
npm ci --cache .tmp/npm-cache
npm run lint:md
npm run lint:spelling
```
