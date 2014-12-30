#!/bin/bash -x

DISTRO=arch
VERSION=2013

# set up base system plus:
#    syslinux (necessary? i don't think it is...)
#    vim (because nano is lame)
#    openssh
# ...any other package from standard Arch repos...
PACKS="base base-devel syslinux openssh vim"

MIRROR1=http://mirror.umoss.org/archlinux
MIRROR2=http://mirror.rit.edu/archlinux

if [[ ${1} == 64 ]]; then
  ARCH=x86_64
else
  if [[ ${1} == 32 ]]; then
    ARCH=i686
  else
    echo "Usage: ${0} 32|64"
    exit 1
  fi
fi

ROOT=${DISTRO}-${VERSION}-${ARCH}

TEMPLATE=$(pwd)/${ROOT}.tar.gz

if [[ "$(whoami)" == "root" ]]; then
    echo "Building template: ${ROOT}"
else
    echo "This script must be run as root (or with sudo)"
    exit 1
fi

cat <<EOF > pacman.conf
[options]
HoldPkg     = pacman glibc
SyncFirst   = pacman
Architecture = ${ARCH}

[core]
Server = ${MIRROR1}/\$repo/os/${ARCH}
Server = ${MIRROR2}/\$repo/os/${ARCH}
Include = /etc/pacman.d/mirrorlist
[extra]
Server = ${MIRROR1}/\$repo/os/${ARCH}
Server = ${MIRROR2}/\$repo/os/${ARCH}
Include = /etc/pacman.d/mirrorlist
[community]
Server = ${MIRROR1}/\$repo/os/${ARCH}
Server = ${MIRROR2}/\$repo/os/${ARCH}
Include = /etc/pacman.d/mirrorlist
EOF

mkarchroot -C pacman.conf ${ROOT} ${PACKS}

if [[ $? -ne 0 ]]; then
    echo "Build failed".
    exit 1
fi

chmod 666 ${ROOT}/dev/null
chmod 666 ${ROOT}/dev/zero
mknod -m 666 ${ROOT}/dev/random c 1 8
mknod -m 666 ${ROOT}/dev/urandom c 1 9
mkdir -m 755 ${ROOT}/dev/pts
mkdir -m 1777 ${ROOT}/dev/shm
mknod -m 666 ${ROOT}/dev/tty c 5 0
mknod -m 666 ${ROOT}/dev/full c 1 7
mknod -m 600 ${ROOT}/dev/initctl p
mknod -m 666 ${ROOT}/dev/ptmx c 5 2


# we don't need any getty entries in a container
sed 's/^.*getty.*$//' -i ${ROOT}/etc/inittab

cd ${ROOT}
tar czvf ${TEMPLATE} .

echo "Created template: ${ROOT}"